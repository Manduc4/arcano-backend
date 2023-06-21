<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
  public function New(BookRequest $request)
  {
    $data = $request->all();

    $new = new Book();
    $new->title = $data['title'];
    $new->author = $data['author'];
    $new->qtd_pages = $data['qtd_pages'];
    $new->gender = $data['gender'];

    try {
      $new->save();

      return response()->json([
        'message' => 'Livro cadastrado com Sucesso!'
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao cadastrar o Livro.',
        'error' => $th
      ], 400);
    }
  }

  public function List()
  {

    try {
      $books = Book::get();

      return response()->json($books, 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao buscar os Livros.',
        'error' => $th
      ], 400);
    }
  }

  public function Individual($id)
  {
    try {
      $state = Book::find($id);

      if (!$state) {
        return response()->json([
          'message' => 'Não encontramos o livro informado.',
        ], 400);
      }

      return response()->json($state, 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao buscar o Livro.',
        'error' => $th
      ], 400);
    }
  }

  public function Update(BookRequest $request, $id)
  {
    $data = $request->all();

    $update = Book::find($id);

    if (!$update) {
      return response()->json([
        'message' => 'Não encontramos o Livro informado.'
      ], 400);
    }

    $update->title = $data['title'];
    $update->author = $data['author'];
    $update->qtd_pages = $data['qtd_pages'];
    $update->gender = $data['gender'];

    try {
      $update->save();

      return response()->json([
        'message' => 'Livro atualizado com Sucesso!'
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao atualizar o Livro.',
        'error' => $th
      ], 400);
    }
  }

  public function Delete($id)
  {
    $delete = Book::find($id);

    if (!$delete) {
      return response()->json([
        'message' => 'Não encontramos o Livro informado.',
      ], 400);
    }
    
    $delete->deleted_at = date('Y-m-d H:i:s');

    try {
      $delete->delete();

      return response()->json([
        'message' => 'Livro removido com sucesso!',
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Ocorreu um erro ao remover o Livro.',
        'error' => $th
      ], 400);
    }
  }
}
