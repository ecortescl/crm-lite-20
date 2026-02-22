<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique(); // Número correlativo
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Usuario que emitió
            
            // Datos del cliente (pueden ser de lead o company)
            $table->string('client_name');
            $table->string('client_rut')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_address')->nullable();
            
            // Fechas
            $table->date('issue_date');
            $table->date('valid_until');
            
            // Items de la cotización (JSON)
            $table->json('items'); // [{description, quantity, unit_price, subtotal}]
            
            // Totales
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_rate', 5, 2); // Porcentaje de impuesto
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('total', 12, 2);
            
            // Notas adicionales
            $table->text('notes')->nullable();
            $table->text('terms')->nullable(); // Términos y condiciones
            
            // Estado
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired'])->default('draft');
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
        });

        Schema::dropIfExists('quotations');
    }
};
