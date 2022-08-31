<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    use HasFactory;
    protected $fillable = ['nome','estado','imagem'];

    public function categorias(){
        // uma cidade pode ter 0 ou muitas categorias
        return $this->hasMany('App\Models\Categoria');
    }
}
