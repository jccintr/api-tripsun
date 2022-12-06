<?php

namespace App\Http\Controllers;

use App\Models\Prestador;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestadorController extends Controller
{
    public function __construct(Prestador $prestadores){
        $this->prestadores = $prestadores;
      }

//===========================================================
// Lista todas os Prestadores GET
//===========================================================
  public function list()
  {
    //  $prestadores = $this->prestadores->get();
      $prestadores = Prestador::orderBy('nome')->get();
        foreach ($prestadores as $prestador) {
          $cidade = Cidade::find($prestador['cidade_id']);
          $prestador['nome_cidade'] = $cidade['nome'];
        }

      return response()->json($prestadores->values()->all(),200);
  }
//============================================================
// Adiciona uma subcategoria POST
//============================================================
  public function add(Request $request)
  {
     $nome = $request->nome;
     $endereco = $request->endereco;
     $bairro = $request->bairro;
     $cep = $request->cep;
     $contato = $request->contato;
     $telefone = $request->telefone;
     $cnpj = $request->cnpj;
     $ie = $request->ie;
     $imagem = $request->file('logotipo');

      if($imagem && $nome){
        $imagem_url = $imagem->store('imagens/prestadores','public');
        $prestador = $this->prestadores->create([
          'nome' => $nome,
          'cidade_id' => $request->cidade_id,
          'logotipo' => $imagem_url,
          'endereco' => $endereco,
          'bairro' => $bairro,
          'cep' => $cep,
          'contato' => $contato,
          'telefone' => $telefone,
          'cnpj' => $cnpj,
          'ie' => $ie
        ]);
        return response()->json($prestador,201);
    } else {
      $array['erro'] = "Requisição mal formatada";
      return response()->json($array,400);
    }
  }
//================================================================
// Recupera um Prestador por Id GET
//================================================================
  public function getById($id)
  {
    $prestador = Prestador::find($id);

   if ($prestador === null){
      return response()->json(['erro'=>'Prestador não encontrado'],404);
   } else {
      return response()->json($prestador,200);
   }
  }
//================================================================
// Atualiza um Prestador POST
//================================================================
  public function update($id,Request $request){

    $imagem = $request->file('imagem');
    $nome = $request->nome;
    if($nome) {
        $prestador = Prestador::find($id);
        $prestador->nome = $nome;
        $prestador->cidade_id = $request->cidade_id;
        $prestador->endereco = $request->endereco;
        $prestador->bairro = $request->bairro;
        $prestador->cep = $request->cep;
        $prestador->contato = $request->contato;
        $prestador->telefone = $request->telefone;
        $prestador->cnpj = $request->cnpj;
        $prestador->ie = $request->ie;

        if($imagem){
          Storage::disk('public')->delete($prestador->imagem);
          $imagem_url = $imagem->store('imagens/prestadores','public');
          $prestador->logotipo = $imagem_url;
        }
        $prestador->save();
        return response()->json($prestador,200);
    } else {
      $array['erro'] = "Campos obrigatórios não informados.";
      return response()->json($array,400);
    }

  }


}
