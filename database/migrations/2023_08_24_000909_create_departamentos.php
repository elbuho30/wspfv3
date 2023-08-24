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
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('estado')->default(true);
            $table->bigInteger('pais_id')->unsigned()->require();
            $table->bigInteger('user_id')->unsigned()->require();
            $table->timestamps();
        });
        Schema::table('departamentos', function ($table) {
            $table->foreign('pais_id')->references('id')->on('paises');
        });
        
        Schema::table('departamentos', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
