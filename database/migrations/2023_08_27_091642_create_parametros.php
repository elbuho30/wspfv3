<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->string('nit');
            $table->string('razon_social');
            $table->string('sigla')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('direccion')->nullable();
            $table->bigInteger('ciudad_id')->unsigned();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->nullable();
            $table->string('url_api_meta')->nullable();
            $table->string('auth_api_key_meta')->nullable();
            $table->string('url_webhook')->nullable();
            $table->string('token_webhook')->nullable(); 
            $table->bigInteger('puerto')->nullable();            
            $table->boolean('estado')->default(true);
            $table->bigInteger('user_id')->unsigned();           
            $table->timestamps();
        });
        Schema::table('parametros', function ($table) {
            $table->foreign('ciudad_id')->references('id')->on('ciudades');
        });
        Schema::table('parametros', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros');
    }
};
