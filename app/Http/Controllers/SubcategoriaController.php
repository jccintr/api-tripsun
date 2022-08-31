<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use Illuminate\Http\Request;

class SubcategoriaController extends Controller
{
    public function __construct(Subcategoria $subcategorias){
        $this->subcategorias = $subcategorias;
      }


      public function list()
      {
          $subcategorias = $this->subcategorias->get();
          //$categorias_ordenado = $categorias->sortBy('nome');
          return response()->json($subcategorias->values()->all(),200);
      }

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
}
