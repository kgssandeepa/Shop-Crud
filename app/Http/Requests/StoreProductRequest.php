<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Classes\ErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'media.*.media_id' => 'nullable|exists:media,id',

        ];
    }
}
