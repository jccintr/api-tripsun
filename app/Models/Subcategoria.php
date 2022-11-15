<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;
    protected $fillable = ['nome','imagem','categoria_id','marcador'];

    public function servicos(){
        // uma cidade pode ter 0 ou muitas categorias
        return $this->hasMany('App\Models\Servico');
    }
}
