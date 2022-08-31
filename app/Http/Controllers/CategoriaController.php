<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
   
    public function __construct(Categoria $categorias){
        $this->categorias = $categorias;
      }


      public function list()
      {
          $categorias = $this->categorias->get();
          //$categorias_ordenado = $categorias->sortBy('nome');
          return response()->json($categorias->values()->all(),200);
      }

      public function add(Request $request)
      {
          $imagem = $request->file('imagem');
          $imagem_url = $imagem->store('imagens/categorias','public');
          $categoria = $this->categorias->create([
            'nome' => $request->nome,
            'imagem' => $imagem_url
          ]);
  
          return response()->json($categoria,201); 
      }

}
