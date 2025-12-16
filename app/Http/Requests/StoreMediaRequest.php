<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Classes\ErrorResponse;

use Illuminate\Contracts\Validation\Validator;


class StoreMediaRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        ErrorResponse::validationError($validator);
    }

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

            'file' => 'required|file|mimes:jpg,png,pdf|max:2048'
        ];
    }
}
