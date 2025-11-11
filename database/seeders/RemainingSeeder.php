<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RemainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'create clients']);
        Permission::create(['name'=>'read clients']);
        Permission::create(['name'=>'update clients']);
        Permission::create(['name'=>'delete clients']);

        Permission::create(['name'=>'create invoices']);
        Permission::create(['name'=>'read invoices']);
        Permission::create(['name'=>'update invoices']);
        Permission::create(['name'=>'delete invoices']);

        Permission::create(['name'=>'create receipts']);
        Permission::create(['name'=>'read receipts']);
        Permission::create(['name'=>'update receipts']);
        Permission::create(['name'=>'delete receipts']);

        Permission::create(['name'=>'create attendance notes']);
        Permission::create(['name'=>'read attendance notes']);
        Permission::create(['name'=>'update attendance notes']);
        Permission::create(['name'=>'delete attendance notes']);

        Permission::create(['name'=>'create follow-up letters']);
        Permission::create(['name'=>'read follow-up letters']);
        Permission::create(['name'=>'update follow-up letters']);
        Permission::create(['name'=>'delete follow-up letters']);

    }
}
