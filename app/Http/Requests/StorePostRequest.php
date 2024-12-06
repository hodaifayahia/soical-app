<?php

namespace App\Http\Requests;

use App\Enums\GroupStatusEnum;
use App\Models\GroupeUser;
use App\Rules\TotalFileSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public static array $extantion =  
   [ 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp', 'svg',
    'mp4', 'avi', 'mkv', 'mov', 'wmv', 'flv', 'webm', 'mpeg',
    'txt', 'csv', 'xml', 'json', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx','pdf'];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string'],
            'attachments' => [ 'array', 'max:50', new TotalFileSize(1* 1024 *1024*1024)], // Max 50 files, total size <= 1 GB (1 * 1024 * 1024 * 1024 bytes)
            'attachments.*' => [
                'file',
                File::types(self::$extantion)
            ],
            'user_id' => 'numeric',
        'group_id' => ['nullable', 'exists:groups,id', function ($attribute, $value, \Closure $fail) {
    $groupuser = GroupeUser::where('user_id', Auth::id())
        ->where('group_id', $value)
        ->where('status', GroupStatusEnum::APPROVED)
        ->exists();

    if (!$groupuser) {
        $fail('You don\'t have permission to perform this action');
    }
}],

        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::id(),
            'body' => $this->input('body')  ?: " ",
        ]);
        
    }
    public function messages()  {
        return [
            'attachments.max' => 'You can upload a maximum of 50 attachments.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.mimes' => 'invalid file type attachments.',
            // Custom message for the TotalFileSize rule
        ];
    }
}
