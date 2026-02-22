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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('lead_status_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            // Se declara la columna aquí y la FK se agrega en la migración de quotations
            // para respetar el orden de creación de tablas.
            $table->foreignId('quotation_id')->nullable();
            
            // Campos de marketing
            $table->string('source')->nullable(); // Origen: Web, Referido, Redes Sociales, etc.
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            
            // Campos para agendamiento
            $table->dateTime('scheduled_at')->nullable();
            $table->text('meeting_notes')->nullable();
            $table->string('meeting_link')->nullable();
            $table->foreignId('scheduled_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Campos para negociación
            $table->decimal('budget', 12, 2)->nullable();
            $table->text('quote_items')->nullable(); // JSON con items cotizados
            
            // Campos para cierre
            $table->string('invoice_number')->nullable();
            $table->decimal('final_amount', 12, 2)->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
