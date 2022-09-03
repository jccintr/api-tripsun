<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;
    protected $fillable = ['categoria_id','subcategoria_id','cidade_id','prestador_id','nome','descricao_curta'];

    public function prestador(){
        return $this->belongsTo('App\Models\Prestador');
    }

    public function subcategorias(){
        return $this->belongsTo('App\Models\Subcategoria');
    }
}
