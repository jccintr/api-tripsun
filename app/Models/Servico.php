<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;
    protected $fillable = [
    'categoria_id',
    'subcategoria_id',
    'cidade_id',
    'prestador_id',
    'nome',
    'descricao_curta',
    'atrativos',
    'duracao',
    'itens_fornecidos',
    'itens_obrigatorios',
    'horario',
    'endereco',
    'ponto_encontro',
    'valor'
  ];

    public function prestador(){
        return $this->belongsTo('App\Models\Prestador');
    }

    public function subcategorias(){
        return $this->belongsTo('App\Models\Subcategoria');
    }
}
