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
        Schema::create('oficinas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion')->nullable()->label('Dirección');
            $table->string('direccion2')->nullable();
            $table->string('url_maps')->nullable();
            $table->string('telefono')->label('Teléfono')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->nullable();
            $table->string('url_web')->nullable();
            $table->string('horario_atencion')->nullable()->label(('Horario atención'));
            $table->boolean('estado')->default(false);         
            $table->bigInteger('ciudad_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
           
            $table->timestamps();
        });
        Schema::table('oficinas', function ($table) {
            $table->foreign('ciudad_id')->references('id')->on('ciudades');
        });
        
        Schema::table('oficinas', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oficinas');
    }
};
