<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Prestador;
use App\Models\Servico;

class CidadeController extends Controller
{
    public function __construct(Cidade $cidades,Categoria $categorias,Subcategoria $subcategorias){
        $this->cidades = $cidades;
        $this->categorias = $categorias;
        $this->subcategorias = $subcategorias;
      }


      public function list()
      {
          $cidades = $this->cidades->get();
          return response()->json($cidades->values()->all(),200);
      }

      public function add(Request $request)
      {
        $imagem = $request->file('imagem');
        $imagem_url = $imagem->store('imagens/cidades','public');
          $cidade = $this->cidades->create([
            'nome' => $request->nome,
            'estado' => $request->estado,
            'imagem' => $imagem_url
          ]);

          return response()->json($cidade,201);
      }




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
                if (!in_array($findCat, $cat)) {
                    array_push($cat,$findCat);
                 }
                 if (!in_array($findSubcat, $subcat)) {
                    array_push($subcat,$findSubcat);
                }
          $servicoLatitude = (float)$servico['latitude'];
          $servicoLongitude = (float)$servico['longitude'];
        
          $servico['imagem'] = $findSubcat['imagem'];
          $servico['distancia'] =  round(sqrt(pow(69.1 * ($servicoLatitude - $lat), 2) + pow(69.1 * ($lng - $servicoLongitude) * cos($servicoLatitude / 57.3), 2)),1);
         
        
          $servico['preco'] = "50,00";
          }

          $cidade['subcategorias']=$subcat;
          $cidade['categorias']=$cat;
        }


          return response()->json($cidade,200);
        }



}
