<?php
// database/migrations/xxxx_xx_xx_create_receipts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receiptes', function (Blueprint $table) {
            $table->id();

            // Core links
            $table->foreignId('invoice_id')
                  ->constrained('invoices')
                  ->cascadeOnDelete();

            $table->foreignId('client_id')
                  ->constrained('clients')
                  ->cascadeOnDelete();

            // Receipt details
            $table->unsignedInteger('receipt_number')->unique();
            $table->string('ref_number')->nullable();           // invoice ref

            // Payment info
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->string('amount_in_words')->nullable();          // "Three Hundred Eighty Pounds Only"
            $table->enum('payment_method', ['cash', 'cheque', 'bacs', 'money_order']);
            $table->string('cheque_number')->nullable();
            $table->date('payment_date');
            $table->text('payment_for')->nullable();                        // description of service

            // Optional
            $table->text('notes')->nullable();
            $table->string('pdf_path')->nullable();             // stored PDF path

            // Audit
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiptes');
    }
};