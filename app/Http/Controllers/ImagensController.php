<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Imagens;
use Illuminate\Support\Facades\Storage;

class ImagensController extends Controller
{
   

 //================================================================
// Adiciona uma Imagem POST
//================================================================ 
    
    public function add(Request $request)
    {
      $array = ['erro'=>''];
      $imagem = $request->file('imagem');
      $servico_id = $request->servico_id;
     

      if($imagem && $servico_id) {
        $imagem_url = $imagem->store('imagens/servicos','public');
        $retorno = Imagens::create([
            'servico_id' => $servico_id,
            'imagem' => $imagem_url
        ]);
    
          return response()->json($retorno,201);
      } else {
        $array['erro'] = "Requisição mal formatada";
        return response()->json($array,400);
      }
    }

//================================================================
// Recupera as imagens de um Serviço GET
//================================================================

public function listByServico($idServico)

{

$imagens = Imagens::where('servico_id',$idServico)->get();

if ($imagens) {

  return response()->json($imagens,200);

} else {

 
  return response()->json(['erro'=>'Imagens não encontradas'],404);

}

}


}
