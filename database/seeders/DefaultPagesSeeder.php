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
        $setup = PageCategory::find(2);

        if ($setup == null)
            return;

        DB::table('pages')->insert([
            'content' => 'Welcome',
            'title' => 'Get Started',
            'category_id' => $setup->id,
            'last_modified_by' => 0,
            'slug' => 'get_started'
        ]);
    }
}
