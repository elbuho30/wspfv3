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
        Schema::create('repconsignaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nro_documento',24);
            $table->string('canal',30)->nullable();
            $table->string('producto',255)->nullable();
            $table->string('valor',255)->nullable();
            $table->string('cuenta_origen',20)->nullable();
            $table->string('banco_consignacion',200)->nullable();
            $table->string('estado_banco',30)->nullable();
            $table->string('estado_erp',30)->nullable();
            $table->string('comentario',255)->nullable();
            $table->string('tipo_fuente',4)->nullable();
            $table->bigInteger('consecutivo_fuente')->nullable();            
            $table->string('radicado',30)->nullable();
            $table->datetime('fecha');
            $table->string('adjunto',255)->nullable();
            $table->string('comentario_adjunto',255)->nullable();
            $table->string('adjunto2',255)->nullable();
            $table->string('adjunto3',255)->nullable();
            $table->string('adjunto4',255)->nullable();
            $table->string('adjunto5',255)->nullable();
            $table->string('adjunto6',255)->nullable();
            $table->string('adjunto7',255)->nullable();
            $table->bigInteger('cliente_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();           
            $table->timestamps();
        });
            Schema::table('repconsignaciones', function ($table) {
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
            Schema::table('repconsignaciones', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repconsignaciones');
    }
};
