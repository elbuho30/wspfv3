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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nro_documento');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('celular');
            $table->string('email');
            $table->string('direccion', 255)->nullable();
            $table->bigInteger('ciudad_id')->unsigned();
            $table->boolean('estado')->default(false);
            $table->bigInteger('user_id')->unsigned();           
            $table->timestamps();
        });
            Schema::table('clientes', function ($table) {
            $table->foreign('ciudad_id')->references('id')->on('ciudades');
        });
            Schema::table('clientes', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
