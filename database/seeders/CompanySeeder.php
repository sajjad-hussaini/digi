<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'create companies']);
        Permission::create(['name'=>'read companies']);
        Permission::create(['name'=>'update companies']);
        Permission::create(['name'=>'delete companies']);
    }
}
