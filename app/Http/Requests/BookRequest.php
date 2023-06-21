<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BookRequest extends FormRequest
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
      'title' => 'required|string',
      'author' => 'required|string',
      'qtd_pages' => 'required|integer',
      'gender' => 'required|string',
    ];
  }

  public function messages()
  {
    return [
      'title.required' => 'O parâmetro :attribute é obrigatório.',
      'author.required' => 'O parâmetro :attribute é obrigatório.',
      'qtd_pages.required' => 'O parâmetro :attribute é obrigatório.',
      'gender.required' => 'O parâmetro :attribute é obrigatório.',
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
