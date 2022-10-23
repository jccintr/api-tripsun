<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CategoriaController extends Controller
{

  public function __construct(Categoria $categorias){
      $this->categorias = $categorias;
    }
//===========================================================
// Lista todas as categorias GET
//===========================================================
  public function list()
  {
      $categorias = $this->categorias->get();
      //$categorias_ordenado = $categorias->sortBy('nome');
      return response()->json($categorias->values()->all(),200);
  }
//============================================================
// Adiciona uma categoria POST
//============================================================
  public function add(Request $request)
  {
      $array = ['erro'=>''];
      $nome = $request->nome;
      $imagem = $request->file('imagem');
      if($imagem && $nome){

        $imagem_url = $imagem->store('imagens/categorias','public');
        $categoria = $this->categorias->create([
          'nome' => $nome,
          'imagem' => $imagem_url
        ]);
        return response()->json($categoria,201);

      } else {

        $array['erro'] = "Requisição mal formatada";
        return response()->json($array,400);

      }

  }
//================================================================
// Recupera uma categoria por Id GET
//================================================================
  public function getById($id)
  {
    $categoria = Categoria::find($id);

   if ($categoria === null){
      return response()->json(['erro'=>'Categoria não encontrada'],404);
   } else {
      return response()->json($categoria,200);
   }
  }
//================================================================
// Atualiza uma categoria POST
//================================================================
public function update($id,Request $request){

  $imagem = $request->file('imagem');
  $nome = $request->nome;

  if($nome) {
      $categoria = Categoria::find($id);
      $categoria->nome = $nome;
      if($imagem){
        Storage::disk('public')->delete($categoria->imagem);
        $imagem_url = $imagem->store('imagens/categorias','public');
        $categoria->imagem = $imagem_url;
      }
      $categoria->save();
      return response()->json($categoria,200);
  } else {
    $array['erro'] = "Campos obrigatórios não informados.";
    return response()->json($array,400);
  }

}
//================================================================
}
