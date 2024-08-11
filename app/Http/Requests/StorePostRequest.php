<?php

namespace App\Http\Requests;

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
            'attachments' => ['array', 'max:50'],
            'attachments.*' => [
                'file',
                File::types(self::$extantion)->max(500 * 1024)
            ],
            'user_id' => 'numeric',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => Auth::id(),
            'body' => $this->input('body') ?: " ",
        ]);
    }
    public function messages()  {
        return [
            'attachments.*'=> 'invalid File',
        ];
    }
}
