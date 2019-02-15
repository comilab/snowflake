<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this
            ->call(UsersTableSeeder::class)
            ->call(PostsTableSeeder::class)
            ->call(PagesTableSeeder::class)
            ->call(SettingsTableSeeder::class)
        ;
    }
}
