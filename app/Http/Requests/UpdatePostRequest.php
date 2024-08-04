<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Retrieve the Post based on the id input from the request
        // $post = Post::where('id', $this->input('id'))
        //             ->where('user_id', Auth::id())
        //             ->first();
        
        // // Return false for authorization (example purpose; actual logic would vary)
        // return !!$post;
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
            'body' => ['nullable','string'],
            'user_id' => 'numeric',
        ];
    }
}
