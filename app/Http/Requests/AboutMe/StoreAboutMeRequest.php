<?php

namespace App\Http\Requests\AboutMe;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAboutMeRequest extends FormRequest
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
            'user_id'         => ['nullable', 'exists:users,id'],
            'title'           => ['nullable', 'string', 'max:255'],
            'name'            => ['nullable', 'string', 'max:255'],
            'slug'            => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'website'         => ['nullable', 'url', 'max:255'],
            'email'           => ['nullable', 'email', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:20', 'regex:/^[\d+\-\s()]+$/'],
            'location'        => ['nullable', 'string', 'max:255'],
            'age'             => ['nullable', 'integer', 'min:0', 'max:150'],
            'bio'             => ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'social_links'    => ['nullable', 'array'],
            'social_links.*'  => ['nullable', 'url'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists'         => 'The selected user does not exist.',
            'slug.alpha_dash'        => 'The slug may only contain letters, numbers, dashes and underscores.',
            'website.url'            => 'The website must be a valid URL.',
            'email.email'            => 'Please provide a valid email address.',
            'phone.regex'            => 'The phone number format is invalid.',
            'age.integer'            => 'The age must be a number.',
            'age.min'                => 'The age must be at least 0.',
            'age.max'                => 'The age may not be greater than 150.',
            'profile_picture.image'  => 'The profile picture must be an image.',
            'profile_picture.mimes'  => 'The profile picture must be a jpeg, png, jpg, gif, or webp file.',
            'profile_picture.max'    => 'The profile picture may not be greater than 2MB.',
            'social_links.array'     => 'Social links must be an array.',
            'social_links.*.url'     => 'Each social link must be a valid URL.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'request_data' => $this->all()
            ], 422)
        );
    }
}
