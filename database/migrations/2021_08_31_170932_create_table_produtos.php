<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('descricao')->nullable();
            $table->string('cpf_cnpj')->nullable();
            $table->tinyInteger('ncm_id')->nullable();
            $table->boolean('ativo')->nullable();

            $table->foreign('ncm_id')
                ->references('ncms')
                ->on('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
