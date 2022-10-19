<?php

namespace App\Http\Controllers;

use App\Models\Prestador;
use App\Models\Cidade;
use Illuminate\Http\Request;

class PrestadorController extends Controller
{
    public function __construct(Prestador $prestadores){
        $this->prestadores = $prestadores;
      }


      public function list()
      {
          $prestadores = $this->prestadores->get();
            foreach ($prestadores as $prestador) {
              $cidade = Cidade::find($prestador['cidade_id']);
              $prestador['nome_cidade'] = $cidade['nome'];
            }

          return response()->json($prestadores->values()->all(),200);
      }

      public function add(Request $request)
      {
          $imagem = $request->file('logotipo');
          $imagem_url = $imagem->store('imagens/prestadores','public');
          $prestador = $this->prestadores->create([
            'nome' => $request->nome,
            'cidade_id' => $request->cidade_id,
            'logotipo' => $imagem_url
          ]);

          return response()->json($prestador,201);
      }
}
