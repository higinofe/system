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
        Schema::create('databases', function (Blueprint $table) {
            $table->id(); // Cria o campo id (chave primária)
            $table->string('name'); // Nome do banco de dados
            $table->unsignedBigInteger('domain_id'); // Chave estrangeira para a tabela domains
            $table->unsignedBigInteger('user_id'); // Chave estrangeira para a tabela users
            $table->integer('usage')->default(0); // Uso atual do banco de dados
            $table->integer('max_quota')->default(100); // Cota máxima de uso do banco de dados (em MB ou outro valor)
            $table->timestamps(); // Campos created_at e updated_at
    
            // Definindo as chaves estrangeiras
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('databases');
    }
};
