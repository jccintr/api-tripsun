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
      Schema::table('prestadores', function (Blueprint $table) {

          $table->string('endereco')->nullable();
          $table->string('bairro')->nullable();
          $table->string('cep')->nullable();
          $table->string('contato')->nullable();
          $table->string('telefone')->nullable();
          $table->string('cnpj')->nullable();
          $table->string('ie')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      $table->dropColumn('endereco');
      $table->dropColumn('bairro');
      $table->dropColumn('cep');
      $table->dropColumn('contato');
      $table->dropColumn('telefone');
      $table->dropColumn('cnpj');
      $table->dropColumn('ie');
    }
};
