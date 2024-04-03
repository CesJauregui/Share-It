<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'title' => 'string|nullable',
            'content' => 'string|required',
            'attachmemt' => [
                'nullable',
                File::image()
                    ->min(1024)
                    ->max(12 * 1024)
                    ->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(500)),
                File::types(['pdf', 'rar', 'zip', 'doc', 'xls'])
                    ->min(1024)
                    ->max(12 * 1024)
            ],
            'user_id' => 'integer|required',
            'type_id' => 'integer|required'
        ];
    }
}
