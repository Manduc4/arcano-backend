<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
  public function Login(LoginRequest $request)
  {
    $credentials = ["email" => $request['email'], 'password' => $request['password']];

    if (Auth::attempt($credentials)) {
      $user = Auth::user();

      $dataToken = $user->createToken('token' . $user->id);

      $token = $dataToken->accessToken;
      $token_expire = $dataToken->token->expires_at;

      return response()->json([
        "message" => "Autenticação realizada com sucesso!",
        "token" => ["value" => $token, "expires" => $token_expire],
        "user" => $user,
      ], 200);
    } else {
      return response()->json([
        "message" => "Credenciais Inválidas!",
      ], 401);
    }
  }

  public function CreateUser(CreateUserRequest $request)
  {
    $data = $request->all();

    $new = new User();
    $new->email = $data['email'];
    $new->name = $data['name'];
    $new->password = $data['password'];

    try {
      $new->save();

      return response()->json([
        'message' => 'Usuário cadastrado com Sucesso!'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao cadastrar o Usuário.',
        'error' => $th
      ], 401);
    }
  }

  public function List()
  {
    $users = User::get();

    return response()->json($users, 200);
  }

  public function Individual($id)
  {
    $user = User::find($id);

    if (!$user) {
      return response()->json([
        "message" => "Não encontramos o Usuário informado.",
      ], 401);
    };

    return response()->json($user, 200);
  }

  public function Update(UpdateUserRequest $request, $id)
  {
    $data = $request->all();

    $update = User::find($id);

    if (!$update) {
      return response()->json([
        "message" => "Não encontramos o Usuário informado."
      ], 401);
    }

    $update->email = $data['email'];
    $update->name = $data['name'];

    try {
      $update->save();

      return response()->json([
        'message' => 'Usuário atualizado com Sucesso!'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao Atualizar o Usuário.',
        'error' => $th
      ]);
    }
  }

  public function Delete($id)
  {
    $delete = User::find($id);

    if (!$delete) {
      return response()->json([
        "message" => "Não encontramos o Usuário informado."
      ], 401);
    }

    try {
      $delete->delete();

      return response()->json([
        'message' => 'Usuário removido com sucesso!'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao remover o Usuário.',
        'error' => $th
      ]);
    }
  }
}
