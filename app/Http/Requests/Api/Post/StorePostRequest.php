<?php

namespace App\Http\Requests\Api\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

/**
 * @property string $title
 * @property string $content
 * @property string $image_url
 * @property string $status
 * @property string $scheduled_time
 * @property array $platform_ids
 */
class StorePostRequest extends FormRequest
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

        if ($accept && str_contains($accept, '/json')) {
            throw new HttpResponseException(
                Response::api($validator->errors()->first(), 400, false, 400)
            );
        }

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
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',
            'image_url'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'status'          => 'required|string|in:draft,scheduled,published',
            'scheduled_time'  => 'nullable|date|required_if:status,scheduled|after_or_equal:now',
            'platform_ids'    => 'required|array|min:1',
            'platform_ids.*'  => 'exists:platforms,id',
        ];
    }
}
