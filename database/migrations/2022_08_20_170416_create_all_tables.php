<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {



        Schema::create('cidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('estado');
            $table->string('imagem');
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('imagem');
            $table->timestamps();
        });

        Schema::create('subcategorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_id');
            $table->string('nome');
            $table->string('imagem');
            //$table->string('marcador');
            $table->timestamps();
             // cria o relacionamento com a tabela categorias
             $table->foreign('categoria_id')->references('id')->on('categorias');
        });

        Schema::create('prestadores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('cidade_id');
            $table->string('logotipo');
            $table->timestamps();
            // cria o relacionamento com a tabela cidades
            $table->foreign('cidade_id')->references('id')->on('cidades');
        });

        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('subcategoria_id');
            $table->unsignedBigInteger('cidade_id');
            $table->unsignedBigInteger('prestador_id');
            $table->string('nome');
            $table->string('descricao_curta');
            //$table->boolean('destaque')->default(false); 
            //$table->float('stars')->default(0);
            //$table->string('latitude')->nullable();
            //$table->string('longitude')->nullable();
             //******************************************
             //$table->string('atrativos')->nullable();
             //$table->string('duracao')->nullable();
             //$table->string('itens_fornecidos')->nullable();
             //$table->string('itens_obrigatorios')->nullable();
             //$table->string('horario')->nullable();
             //$table->string('endereco')->nullable();
             //$table->string('ponto_encontro')->nullable();
             //$table->integer('valor')->default(0);
          

            $table->timestamps();
            // cria o relacionamento com a tabela categorias
            $table->foreign('categoria_id')->references('id')->on('categorias');
            // cria o relacionamento com a tabela subcategorias
            $table->foreign('subcategoria_id')->references('id')->on('subcategorias');
            // cria o relacionamento com a tabela cidades
            $table->foreign('cidade_id')->references('id')->on('cidades');
            // cria o relacionamento com a tabela prestadores
            $table->foreign('prestador_id')->references('id')->on('prestadores');
        });





    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cidades');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('subcategorias');
        Schema::dropIfExists('prestadores');
        Schema::dropIfExists('servicos');
    }
};
