<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    use HasFactory;
    protected $table = 'prestadores';
    protected $fillable = ['nome','cidade_id','logotipo','endereco','bairro','cep','contato','telefone','cnpj','ie'];

    public function servicos(){
        // uma cidade pode ter 0 ou muitas categorias
        return $this->hasMany('App\Models\Servico');
    }
}
