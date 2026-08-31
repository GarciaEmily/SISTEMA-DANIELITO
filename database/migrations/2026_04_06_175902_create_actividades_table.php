<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('actividades', function (Blueprint $table) {
        $table->id();

        $table->foreignId('creado_por')->constrained('users')->onDelete('cascade');

        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->date('fecha_actividad');

        $table->string('tipo'); // regular / intervencion

        $table->boolean('activa')->default(true);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividads');
    }
};
