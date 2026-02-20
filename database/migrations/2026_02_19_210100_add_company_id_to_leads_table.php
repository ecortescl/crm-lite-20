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
        Schema::table('leads', function (Blueprint $table) {
            // Agregar relación con companies
            $table->foreignId('company_id')->nullable()->after('company')->constrained()->onDelete('set null');
            
            // Renombrar el campo company a contact_company para evitar confusión
            // Este campo ahora será solo para texto libre cuando no hay empresa asociada
            $table->renameColumn('company', 'contact_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->renameColumn('contact_company', 'company');
        });
    }
};
