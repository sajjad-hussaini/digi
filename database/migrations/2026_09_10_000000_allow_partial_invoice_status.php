<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE invoices MODIFY status ENUM('paid', 'unpaid', 'partial') NOT NULL DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        DB::statement("UPDATE invoices SET status = 'unpaid' WHERE status = 'partial'");
        DB::statement("ALTER TABLE invoices MODIFY status ENUM('paid', 'unpaid') NOT NULL DEFAULT 'unpaid'");
    }
};