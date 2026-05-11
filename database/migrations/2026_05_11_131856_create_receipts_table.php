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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('receipt_no');        // 0109
            $table->string('ref_no');            // 0110
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->text('amount_in_words');     // Three Hundred Eighty pound only
            $table->text('for_payment_of');      // Description
            $table->string('received_by');       // Mohamad Salim Kureshi
            $table->enum('paid_by', ['cash', 'cheque', 'money_order', 'bacs']);
            $table->string('cheque_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
