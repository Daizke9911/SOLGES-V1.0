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
        Schema::create('visitas_visitas', function (Blueprint $table) {
            $table->id();
            $table->string('solicitud_id');
            $table->integer('programada_anio');
            $table->integer('programada_mes');
            $table->integer('programada_dia');
            $table->unsignedBigInteger('estatus_id');
            $table->text('descripcion');
            $table->text('observacion');

            $table->foreign('solicitud_id')->references('solicitud_id')->on('solicitudes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('estatus_id')->references('estatus_id')->on('estatus')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas_visitas');
    }
};
