<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRecoveryRequest;
use App\Mail\UserRecovery;
use App\Models\User;
use App\Models\UserLogAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Throwable;

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

      $newLogAccess = new UserLogAccess();
      $newLogAccess->user_id = $user->id;
      $newLogAccess->ip = $request->ip();
      $newLogAccess->last_access = date('Y-m-d H:i:s');

      $newLogAccess->save();

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

  public function Logout()
  {
    $user = Auth::user()->token();
    $user->revoke();

    return response()->json([
      "message" => "Logout realizado com sucesso!",
    ], 200);
  }

  public function Recovery(UserRecoveryRequest $request)
  {
    $email = $request->only('email');

    $generatedPass = "ARCANO" . bin2hex(openssl_random_pseudo_bytes(4));

    $user = User::where('email', $email)->first();

    $user->password = Hash::make($generatedPass);

    try {
      $user->save();
      $user->new = $generatedPass;

      Mail::to($user)->send(new UserRecovery($user));

      return response()->json([
        'message' => 'Recuperação de senha solicitada.'
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao solicitar a Recuperação de senha.',
        'error' => $th
      ], 400);
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

  public function UpdateUser(UpdateUserRequest $request, $id)
  {
    $data = $request->all();

    $update = User::find($id);
    $update->email = $data['email'];
    $update->name = $data['name'];

    try {
      $update->save();

      return response()->json([
        'message' => 'Usuário atualizado com Sucesso!',
        'user' => $update
      ]);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao atualizar o Usuário.',
        'error' => $th
      ], 400);
    }
  }

  public function UpdatePassword(UpdatePasswordRequest $request)
  {
    $auth_id = Auth::user()->id;
    $update = User::find($auth_id);

    if (!$update) {
      return response()->json([
        "message" => "Não encontramos informações referente ao usuário autenticado.",
      ], 400);
    };

    // if (!Hash::check($request->password, $update->password)) {
    //   return response()->json([
    //     "error" => "A senha atual não confere.",
    //   ], 400);
    // }

    $update->password = Hash::make($request['password']);

    try {
      $update->save();

      return response()->json([
        'message' => 'Senha atualizada com sucesso!',
      ]);
    } catch (Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao atualizar a Senha.',
        'error' => $th
      ]);
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
