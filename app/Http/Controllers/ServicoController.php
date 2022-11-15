<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servico;
use App\Models\Cidade;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Prestador;

class ServicoController extends Controller
{
    public function __construct(Servico $servicos,Categoria $categorias,Prestador $prestadores,Subcategoria $subcategorias){
        $this->servicos = $servicos;
        $this->categorias = $categorias;
        $this->subcategorias = $subcategorias;
        $this->prestadores = $prestadores;
      }

//===========================================================
// Lista todas os Serviços GET
//===========================================================
public function list()
{
    $servicos = $this->servicos->with('prestador')->get();

    foreach ($servicos as $servico) {
      $cidade = Cidade::find($servico['cidade_id']);
      $servico['nome_cidade'] = $cidade['nome'];
    }

    //$categorias_ordenado = $categorias->sortBy('nome');
    return response()->json($servicos->values()->all(),200);
}
//============================================================
// Adiciona um Serviço POST
//============================================================
public function add(Request $request)
{

 $categoria = $request->categoria_id;
 $subcategoria = $request->subcategoria_id;
 $cidade = $request->cidade_id;
 $prestador = $request->prestador_id;
 $nome = $request->nome;
 $latitude = $request->latitude;
 $longitude = $request->longitude;

if($categoria && $subcategoria && $cidade && $prestador && $nome && $latitude && $longitude){

  $servico = $this->servicos->create([
    'nome' => $nome,
    'prestador_id' => $prestador,
    'categoria_id' => $categoria,
    'subcategoria_id' => $subcategoria,
    'cidade_id' => $cidade,
    'destaque' => $request->destaque,
    'endereco' => $request->endereco,
    'ponto_encontro'=> $request->ponto_encontro,
    'latitude' => $latitude,
    'longitude' => $longitude,
    'descricao_curta' => $request->descricao_curta,
    'itens_fornecidos' => $request->itens_fornecidos,
    'itens_obrigatorios' => $request->itens_obrigatorios,
    'atrativos' => $request->atrativos,
    'horario' => $request->horario,
    'duracao' => $request-> duracao,
    'percentual_plataforma' => $request->percentual_plataforma,
    'valor' => $request->valor,
    'stars' => 5.0
  ]);
  return response()->json($servico,201);

}else {
  $array['erro'] = "Requisição mal formatada. ". $categoria;
  return response()->json($array,400);
}


}

//================================================================
// Recupera um Serviço por Id GET
//================================================================
public function getById($id) {

     $servico = Servico::find($id);

     if ($servico === null){
        return response()->json(['erro'=>'Serviço não encontrado'],404);
     } else {
        return response()->json($servico,200);
     }

}
//================================================================
// Atualiza um Prestador POST
//================================================================
public function update($id,Request $request){

  $categoria = $request->categoria_id;
  $subcategoria = $request->subcategoria_id;
  $cidade = $request->cidade_id;
  $prestador = $request->prestador_id;
  $nome = $request->nome;
  $latitude = $request->latitude;
  $longitude = $request->longitude;

  if($categoria && $subcategoria && $cidade && $prestador && $nome && $latitude && $longitude){
      $servico = Servico::find($id);
      $servico->nome = $nome;
      $servico->cidade_id = $cidade;
      $servico->categoria_id = $categoria;
      $servico->subcategoria_id = $subcategoria;
      $servico->prestador_id = $prestador;
      $servico->descricao_curta = $request->descricao_curta;
      $servico->atrativos = $request->atrativos;
      $servico->duracao = $request-> duracao;
      $servico->itens_fornecidos = $request->itens_fornecidos;
      $servico->itens_obrigatorios = $request->itens_obrigatorios;
      $servico->horario = $request->horario;
      $servico->ponto_encontro = $request->ponto_encontro;
      $servico->percentual_plataforma = $request->percentual_plataforma;
      $servico->valor = $request->valor;
      $servico->destaque = $request->destaque;
      $servico->latitude = $latitude;
      $servico->longitude = $longitude;
      $servico->endereco = $request->endereco;
      $servico->save();
      return response()->json($servico,200);
  } else {
     $array['erro'] = "Campos obrigatórios não informados.";
     return response()->json($array,400);
  }
}
//=========================================
// outros
//=========================================
      public function seed() {

        $cidadeId = 3;
        $baseLatitude = '-22.4';
        $baseLongitude = '-45.6';

        $aventuras = ['Legal','Muito Legal','Imperdível','Emocionante'];
        $nomeServico = "Aventura ";
        $descricao_curta = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        for($i=0;$i<40;$i++) {

          $novoServico = new Servico();
          $idSubCategoria = rand(2,16);
          $subcategoria =  $this->subcategorias->find($idSubCategoria);
          $categoria = $this->categorias->find($subcategoria->categoria_id);
          $novoServico->cidade_id = $cidadeId;
          $novoServico->categoria_id = $categoria->id;
          $novoServico->subcategoria_id = $idSubCategoria;
          $novoServico->prestador_id = rand(6,10);
          $novoServico->nome = $nomeServico.$aventuras[rand(0,3)];
          $novoServico->descricao_curta = $descricao_curta;
          $novoServico->stars = rand(3, 4).'.'.rand(0, 9);
          $novoServico->destaque = false;
          $novoServico->latitude =  $baseLatitude.rand(0,9).'30907';
          $novoServico->longitude = $baseLongitude.rand(0,9).'82795';
          $novoServico->save();

        }
        $retorno = ['mensagem' => 'Serviços criado com sucesso.'];
        return response()->json($retorno,201);

      }
//============================================================
      public function getCityByCoords(Request $request){

        $key = env('MAPS_KEY', null);
        $lat = $request->latitude;
        $lng = $request->longitude;
        $coord = urlencode($lat.",".$lng);
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$coord.'&key='.$key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($res,true);
        $logradouro = $data['results'][0]['address_components'][1]['short_name'];
        $numero = $data['results'][0]['address_components'][0]['short_name'];
        $nome = $data['results'][0]['address_components'][3]['long_name'];
        $estado = $data['results'][0]['address_components'][4]['short_name'];
        $cep = $data['results'][0]['address_components'][6]['short_name'];
        $ret = [];
        $ret['nome'] = $nome;
        $ret['estado'] = $estado;
        $ret['cep'] = $cep;
        $ret['logradouro'] = $logradouro;
        $ret['numero'] = $numero;

        return response()->json($ret,200);

      }
//=====================================================================
      public function searchGeo(Request $request) {
        $key = env('MAPS_KEY', null);

        $lat = $request->latitude;
        $lng = $request->longitude;

        $coord = urlencode($lat.",".$lng);
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$coord.'&key='.$key;

       /*
        $city = $request->city;
        $city = urlencode($city);
        if(!empty($city)){
              $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$city.'&key='.$key;
        }
        */

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

//================================================================
    public function searchGeo2(Request $request) {
      $key = env('MAPS_KEY', null);
      $city = $request->city;
      $city = urlencode($city);
      $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$city.'&key='.$key;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $res = curl_exec($ch);
      curl_close($ch);
      return json_decode($res, true);
  }


}
