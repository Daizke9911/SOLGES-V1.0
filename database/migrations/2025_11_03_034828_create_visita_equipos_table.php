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
        Schema::create('visita_equipos', function (Blueprint $table) {
            $table->id('visita_id');
            $table->unsignedBigInteger('cedula');
            $table->string('cargo');

            $table->foreign('cedula')->references('cedula')->on('personas')->onUpdate('cascade')->onDelete('cascade');/* 
            $table->foreign('cargo')->references('cargo')->on('personas_cargo_visitas')->onUpdate('cascade')->onDelete('cascade'); */

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_equipos');
    }
};
