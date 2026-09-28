<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visa_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });
        $names = collect(['Appeal', 'Work Visa', 'Student Visa', 'Spouse Visa', 'Visitor Visa', 'Settlement Visa']);
        foreach (['clients' => 'visa_type', 'templates' => 'matter_type'] as $table => $column) {
            if (Schema::hasColumn($table, $column)) $names = $names->merge(DB::table($table)->whereNotNull($column)->distinct()->pluck($column));
        }
        foreach ($names->filter()->unique() as $name) {
            DB::table('visa_types')->insertOrIgnore(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('visa_types');
    }
};
