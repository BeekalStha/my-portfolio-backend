<?php

namespace App\Http\Requests\Skill;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSkillRequest extends FormRequest
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
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string','unique'],
            'description' => ['nullable', 'string', 'max:255'],
            'proficiency' => ['nullable', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'in:beginner,intermediate,advanced,expert'], // Skill level
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
