<?php

use App\Client;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('authority_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class);
            $table->string('solicitor_name');
            $table->string('firm_name');
            $table->string('purpose');
            $table->string('client_address');
            $table->string('passport_no');
            $table->string('file_path')->nullable(); // PDF path
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authority_letters');
    }
};
