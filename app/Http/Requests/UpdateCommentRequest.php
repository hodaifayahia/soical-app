<?php

namespace App\Http\Requests;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class UpdateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Retrieve the comment based on the id input from the request
        $comment = $this->route('comment');
        // Return false for authorization (example purpose; actual logic would vary)
        return $comment->user_id == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'comment' => 'required'
        ];
    }
}
