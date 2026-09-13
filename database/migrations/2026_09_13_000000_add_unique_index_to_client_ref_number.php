<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $clients = DB::table('clients')
            ->whereNotNull('ref_number')
            ->orderBy('id')
            ->get(['id', 'ref_number']);
        $usedReferences = [];
        $reservedReferences = array_fill_keys($clients->pluck('ref_number')->all(), true);
        $nextReference = $clients->max(fn ($client) => (int) $client->ref_number) + 1;

        foreach ($clients as $client) {
            if (isset($usedReferences[$client->ref_number])) {
                do {
                    $replacement = str_pad((string) $nextReference++, 6, '0', STR_PAD_LEFT);
                } while (isset($reservedReferences[$replacement]));

                DB::table('clients')
                    ->where('id', $client->id)
                    ->update(['ref_number' => $replacement]);
                $client->ref_number = $replacement;
                $reservedReferences[$replacement] = true;
            }

            $usedReferences[$client->ref_number] = true;
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->unique('ref_number', 'clients_ref_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique('clients_ref_number_unique');
        });
    }
};