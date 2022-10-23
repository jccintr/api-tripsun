<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubcategoriaController extends Controller
{

  public function __construct(Subcategoria $subcategorias){
      $this->subcategorias = $subcategorias;
    }

//===========================================================
// Lista todas as Subcategorias GET
//===========================================================
  public function list()
  {

    $subcategorias = $this->subcategorias->get();
    foreach ($subcategorias as $subcategoria) {
        $categoria = Categoria::find($subcategoria['categoria_id']);
        $subcategoria['nome_categoria'] = $categoria['nome'];
    }
    return response()->json($subcategorias->values()->all(),200);

  }
//============================================================
// Adiciona uma subcategoria POST
//============================================================
  public function add(Request $request)
  {
      $imagem = $request->file('imagem');
      $imagem_url = $imagem->store('imagens/subcategorias','public');
      $categoria = $this->subcategorias->create([
        'nome' => $request->nome,
        'imagem' => $imagem_url,
        'categoria_id' => $request->categoria_id
      ]);

      return response()->json($categoria,201);
  }
//================================================================
// Recupera uma Subcategoria por Id GET
//================================================================
public function getById($id)
{
  $subcategoria = Subcategoria::find($id);

 if ($subcategoria === null){
    return response()->json(['erro'=>'Subcategoria não encontrada'],404);
 } else {
    return response()->json($subcategoria,200);
 }
}
//================================================================
// Atualiza uma categoria POST
//================================================================
public function update($id,Request $request){

  $imagem = $request->file('imagem');
  $nome = $request->nome;
  $idCategoria = $request->categoria_id;

  if($nome and $idCategoria) {
      $subcategoria = Subcategoria::find($id);
      $subcategoria->nome = $nome;
      $subcategoria->categoria_id = $idCategoria;
      if($imagem){
        Storage::disk('public')->delete($subcategoria->imagem);
        $imagem_url = $imagem->store('imagens/subcategorias','public');
        $subcategoria->imagem = $imagem_url;
      }
      $subcategoria->save();
      return response()->json($subcategoria,200);
  } else {
    $array['erro'] = "Campos obrigatórios não informados.";
    return response()->json($array,400);
  }

}

}
