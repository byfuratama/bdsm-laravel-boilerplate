<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'username' => 'superadmin',
                'name' => 'superadmin',
                'password' => bcrypt('5up3r'),
                'role' => 'superadmin'
            ],
            [
                'username' => 'admin',
                'name' => 'admin',
                'password' => bcrypt('admin'),
                'role' => 'admin'
            ],
        ]);
            
        DB::table('test')->truncate();
        for ($i=0; $i < 50; $i++) { 
            DB::table('test')->insert([
                [
                    'str' => 'string ' . $i,
                    'bool' => 0,
                    'date' => '2019-10-10'
                ]
            ]);
        }
    }
}
