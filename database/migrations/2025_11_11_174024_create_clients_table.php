<?php

use App\Company;
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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class);
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('sir_name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('visa_type')->nullable();
            $table->string('country')->nullable();
            $table->date('dob')->nullable();
            $table->date('visa_expiry_date')->nullable();
            $table->enum('status', ['Active', 'Closed', 'Pending', 'Archived'])->default('Active');
            $table->string('priority')->nullable();
            $table->string('court_type')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
