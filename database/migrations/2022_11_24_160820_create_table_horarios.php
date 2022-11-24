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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servico_id');
            $table->date('data');
            $table->time('hora');
            $table->time('duracao');
            $table->integer('quant')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            // cria o relacionamento com a tabela servicos
            $table->foreign('servico_id')->references('id')->on('servicos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() 
    {
        Schema::dropIfExists('horarios');
    }
};
