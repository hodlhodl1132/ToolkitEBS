<?php

namespace Database\Seeders;

use App\Models\PageCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $root = PageCategory::find(1);
        $setup = PageCategory::find(2);

        if ($setup == null || $root == null)
            return;

        DB::table('pages')->insert([
            'content' => 'Welcome',
            'title' => 'Get Started',
            'category_id' => $setup->id,
            'last_modified_by' => 0,
            'slug' => 'get_started'
        ]);

        DB::table('pages')->insert([
            'content' => 'Welcome',
            'title' => 'Privacy Policy',
            'category_id' => $root->id,
            'last_modified_by' => 0,
            'slug' => 'privacy_policy'
        ]);

        DB::table('pages')->insert([
            'content' => 'Welcome',
            'title' => 'Terms of Use',
            'category_id' => $root->id,
            'last_modified_by' => 0,
            'slug' => 'terms_of_use'
        ]);
    }
}
