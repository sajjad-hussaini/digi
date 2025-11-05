<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Database\Seeders\CompanySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingsTableSeeder::class,
            UsersTableSeeder::class,
            PermissionsTableSeeder::class,
            FileTypesSeeder::class,
            CompanySeeder::class
        ]);
    }
}
