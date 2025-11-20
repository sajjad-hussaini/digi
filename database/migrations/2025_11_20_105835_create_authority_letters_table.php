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
            $table->string('full_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('client_address')->nullable();
            $table->string('purpose')->nullable();
            $table->string('file_path')->nullable(); // PDF path
            $table->string('first_name')->nullable();
            $table->string('sir_name')->nullable();
            $table->string('solicitor_name')->nullable();
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