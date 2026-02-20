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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('business_name'); // Razón Social
            $table->string('rut')->unique(); // RUT de la empresa (ej: 76.123.456-7)
            $table->string('fantasy_name')->nullable(); // Nombre de Fantasía
            $table->string('giro')->nullable(); // Giro o actividad económica
            
            // Contacto
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            
            // Dirección
            $table->string('address')->nullable();
            $table->string('commune')->nullable(); // Comuna
            $table->string('city')->nullable(); // Ciudad
            $table->string('region')->nullable(); // Región
            
            // Información adicional
            $table->text('notes')->nullable();
            $table->enum('size', ['micro', 'small', 'medium', 'large'])->nullable(); // Tamaño de empresa
            $table->string('industry')->nullable(); // Industria/Sector
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
