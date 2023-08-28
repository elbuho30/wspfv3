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
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('estado')->default(true);
            $table->bigInteger('departamento_id')->unsigned()->require();
            $table->bigInteger('user_id')->unsigned()->require();
            $table->timestamps();
        });
        Schema::table('ciudades', function ($table) {
            $table->foreign('departamento_id')->references('id')->on('departamentos');
        });
        
        Schema::table('ciudades', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
