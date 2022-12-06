<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Prestador;
use App\Models\Servico;
use App\Models\Imagens;
use Illuminate\Support\Facades\Storage;

class CidadeController extends Controller
{
    public function __construct(Cidade $cidades,Categoria $categorias,Subcategoria $subcategorias){
        $this->cidades = $cidades;
        $this->categorias = $categorias;
        $this->subcategorias = $subcategorias;
      }

//********************************************************
      public function list()
      {
          //$cidades = $this->cidades->get();
          $cidades = Cidade::orderBy('nome')->get();
          return response()->json($cidades->values()->all(),200);
      }
//********************************************************
      public function add(Request $request)
      {
        $array = ['erro'=>''];
        $imagem = $request->file('imagem');
        $nome = $request->nome;
        $estado = $request->estado;

        if($imagem && $nome && $estado) {
          $imagem_url = $imagem->store('imagens/cidades','public');
          $cidade = $this->cidades->create([
            'nome' => $nome,
            'estado' => $estado,
            'imagem' => $imagem_url
          ]);
            return response()->json($cidade,201);
        } else {
          $array['erro'] = "Requisição mal formatada";
          return response()->json($array,400);
        }
  }
//***********************************************************
      public function get(Request $request)
     {
        $cidade = $this->cidades->find($request->id);
        $lat = (float)$request->lat;
        $lng = (float)$request->lng;
        if ($cidade === null){
           return response()->json(['erro'=>'Cidade não encontrada'],404);
        }
        else {

          $cidade['categorias'] = [];
          $cidade['subcategorias'] = [];

          $subcat = [];
          $cat = [];
          // pega os serviços da cidade
          $cidade['servicos'] = Servico::where('cidade_id',$request->id)->with('prestador')->get();

          // pega as categorias e subcategorias da cidade
          foreach($cidade['servicos'] as  $servico) {

                $findCat = $this->categorias->find($servico['categoria_id']);
                $servico['categoria'] = $findCat['nome'];
                $findSubcat = $this->subcategorias->find($servico['subcategoria_id']);
                $servico['subcategoria'] = $findSubcat['nome'];
                if (!in_array($findCat, $cat)) {
                    array_push($cat,$findCat);
                 }
                if (!in_array($findSubcat, $subcat)) {
                    array_push($subcat,$findSubcat);
                }
          $servicoLatitude = (float)$servico['latitude'];
          $servicoLongitude = (float)$servico['longitude'];
          //pega as imagens do serviço
          $servico['imagens'] = Imagens::where('servico_id',$servico['id'])->get();
          
          $servico['imagem'] = $findSubcat['imagem'];
          $servico['marcador'] = $findSubcat['marcador'];
          $servico['distancia'] =  round(sqrt(pow(69.1 * ($servicoLatitude - $lat), 2) + pow(69.1 * ($lng - $servicoLongitude) * cos($servicoLatitude / 57.3), 2)),1);
          $servico['valor'] = $servico['valor'] / 100;

          //$servico['preco'] = rand(3,9).rand(0,9).',00';
          }

          $cidade['subcategorias']=$subcat;
          $cidade['categorias']=$cat;
        }


          return response()->json($cidade,200);
        }
//*******************************************************************
        public function getById($id)
       {
          $cidade = Cidade::find($id);

         if ($cidade === null){
            return response()->json(['erro'=>'Cidade não encontrada'],404);
         } else {
            return response()->json($cidade,200);
         }
   }
//******************************************************************
  public function update($id,Request $request){

    $imagem = $request->file('imagem');
    $nome = $request->nome;
    $estado = $request->estado;

    if($nome && $estado) {
        $cidade = Cidade::find($id);
        $cidade->nome = $nome;
        $cidade->estado = $estado;
        if($imagem){
          Storage::disk('public')->delete($cidade->imagem);
          $imagem_url = $imagem->store('imagens/cidades','public');
          $cidade->imagem = $imagem_url;
        }
        $cidade->save();
        return response()->json($cidade,200);
    } else {
      $array['erro'] = "Campos obrigatórios não informados.";
      return response()->json($array,400);
    }

  }

}
