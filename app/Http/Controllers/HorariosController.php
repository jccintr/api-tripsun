<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;

class HorariosController extends Controller
{
//================================================================
// Adiciona um Horario POST
//================================================================

public function add(Request $request)
{
  $array = ['erro'=>''];

  $servico_id = $request->servico_id;
  $ano = intval($request->ano);
  $mes = intval($request->mes);
  $dia = intval($request->dia);
  $hora = $request->hora;
  $duracao = $request->duracao;
  $quant = intval($request->quant);
  $ativo = $request->ativo;


  $dia = ($dia < 10) ? '0'.$dia : $dia;
  $mes = ($mes < 10) ? '0'.$mes : $mes;

  $data = $ano.'-'.$mes.'-'.$dia;
 
  if(checkdate( $mes,$dia,$ano)) {  // data válida

    $horarios = Horario::select()->where('servico_id', $servico_id)->where('data', $data)->where('hora',$hora)->count();;
    $data_atual = date('Y-m-d');
  
    if($data < $data_atual){
        $array['erro'] = "Data inferior a data atual.";
        return response()->json($array,400);
    }
    if ($horarios===0){ // horario ainda não cadastrado
        $retorno = Horario::create([
            'servico_id' => $servico_id,
            'data' => $data,
            'hora' => $hora,
            'duracao' => $duracao,
            'quant' => $quant,
            'ativo' => $ativo
        ]);
        return response()->json($retorno,201);

    }
    else {
        $array['erro'] = "Horário já cadastrado";
        return response()->json($array,400);
    }
   

  } else {
        $array['erro'] = "Data inválida";
        return response()->json($array,400);
  }


  }

//===============================================================
// Recupera os horarios de um Serviço GET
//================================================================

public function listByServico($idServico)

{

  $horarios = Horario::where('servico_id',$idServico)->get();
  if ($horarios) {
    return response()->json($horarios,200);
  } else {
    return response()->json(['erro'=>'Horarios não encontradas'],404);
  }

}


}
