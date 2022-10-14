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
        Schema::table('servicos', function (Blueprint $table) {
            $table->string('atrativos')->nullable();
            $table->string('duracao')->nullable();
            $table->string('itens_fornecidos')->nullable();
            $table->string('itens_obrigatorios')->nullable();
            $table->string('horario')->nullable();
            $table->string('endereco')->nullable();
            $table->string('ponto_encontro')->nullable();
            $table->integer('valor')->default(0);
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn('atrativos');
            $table->dropColumn('duracao');
            $table->dropColumn('itens_fornecidos');
            $table->dropColumn('itens_obrigatorios');
            $table->dropColumn('horario');
            $table->dropColumn('endereco');
            $table->dropColumn('ponto_encontro');
            $table->dropColumn('valor');
        });
    }
};
