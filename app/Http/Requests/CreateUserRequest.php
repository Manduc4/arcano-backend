<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CreateUserRequest extends FormRequest
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
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|string',
    ];
  }

  public function messages()
  {
    return [
      'name.required' => 'O parâmetro :attribute é obrigatório.',
      'email.required' => 'O parâmetro :attribute é obrigatório.',
      'email.unique:users' => 'Este email já está em uso.',
      'password' => 'O parâmetro :attribute é obrigatório.',
    ];
  }

  public function response(array $errors)
  {
    return new JsonResponse([
      'status' => '422',
      'errors' => $errors,
    ], 422);
  }

  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
  }
}
