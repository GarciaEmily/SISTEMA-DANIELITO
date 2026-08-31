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
    Schema::create('ninos', function (Blueprint $table) {
        $table->id();
        $table->string('codigo')->nullable();

        $table->foreignId('maestro_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');

        $table->string('nombres');
        $table->string('apellidos');
        $table->date('fecha_nacimiento')->nullable();
        $table->integer('edad')->nullable();
        $table->string('contacto')->nullable();
        $table->string('curso')->nullable();
        $table->string('colegio')->nullable();

        $table->boolean('vulnerable')->default(false);
        $table->text('motivo_vulnerabilidad')->nullable();

        $table->text('observaciones')->nullable();

        $table->boolean('fue_al_encuentro')->default(false);
        $table->boolean('bautizado')->default(false);
        $table->boolean('asiste_iglesia')->default(false);

        $table->string('nombre_iglesia')->nullable();
        $table->string('nombre_celula')->nullable();

        $table->boolean('activo')->default(true);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ninos');
    }
};
