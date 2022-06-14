<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //SUPER ADMIN
        DB::table('users')->insert([
            'name' => 'hodlhodl',
            'provider_id' => '124055459',
            'email' => '1@1.com',
            'provider_token' => '0',
            'refresh_token' => '0'
        ]);
        //ADMIN
        DB::table('users')->insert([
            'name' => 'sirrandoo',
            'provider_id' => '32268983',
            'email' => '2@1.com',
            'provider_token' => '0',
            'refresh_token' => '0'
        ]);
        //COMMUNITY MANAGER
        DB::table('users')->insert([
            'name' => 'saschahi',
            'provider_id' => '35373551',
            'email' => '3@1.com',
            'provider_token' => '0',
            'refresh_token' => '0'
        ]);
        
        $this->call([
            PermissionsSeeder::class,
            PageCategorySeeder::class,
            DefaultPagesSeeder::class,
            UserSeeder::class
        ]);
    }
}
