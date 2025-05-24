<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $accept = request()->header('Accept');

        if ($accept && str_contains($accept, '/json'))
            throw new HttpResponseException(
                Response::api($validator->errors()->first(), 400, false, 400)
            );
        else
            parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|email:dns|unique:users,email',
            'password' => 'required|string|min:8|regex:/[A-Za-z]/|regex:/[0-9]/|confirmed',
        ];
    }
    public function messages(): array
    {
        return [
            'password.regex' => __('message.The password must contain at least one letter and one number.'),
        ];
    }
}
