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


      public function list()
      {
          $servicos = $this->servicos->with('prestador')->get();
          //$categorias_ordenado = $categorias->sortBy('nome');
          return response()->json($servicos->values()->all(),200);
      }

      public function add(Request $request)
      {
          
          $servico = $this->servicos->create([
            'categoria_id' => $request->categoria_id,
            'subcategoria_id' => $request->subcategoria_id,
            'cidade_id' => $request->cidade_id,
            'prestador_id' => $request->prestador_id,
            'nome' => $request->nome,
            'descricao_curta' => $request->descricao_curta
          ]);
  
          return response()->json($servico,201); 
      }



      public function seed() {

        $cidadeId = 1;
        
        $aventuras = ['Legal','Muito Legal','Imperdível','Emocionante'];
        $nomeServico = "Aventura ";
        $descricao_curta = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        
        for($i=0;$i<30;$i++) {

          $novoServico = new Servico();
          $idSubCategoria = rand(2,16);
          $subcategoria =  $this->subcategorias->find($idSubCategoria);
          $categoria = $this->categorias->find($subcategoria->categoria_id);
          $novoServico->cidade_id = $cidadeId;
          $novoServico->categoria_id = $categoria->id;
          $novoServico->subcategoria_id = $idSubCategoria;
          $novoServico->prestador_id = rand(1,5);
          $novoServico->nome = $nomeServico.$aventuras[rand(0,3)];
          $novoServico->descricao_curta = $descricao_curta;
          $novoServico->save();

        }
        $retorno = ['mensagem' => 'Serviços criado com sucesso.'];
        return response()->json($retorno,201); 

      }
}

