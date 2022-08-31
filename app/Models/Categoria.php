<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['nome','imagem'];

    public function cidades(){
        return $this->belongsTo('App\Models\Cidade');
    }
}
