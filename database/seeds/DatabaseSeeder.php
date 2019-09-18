<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    //Seeder berfungsi untuk memasukkan 'seed' data ke database
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
            [
                'username' => 'kasir',
                'name' => 'kasir',
                'password' => bcrypt('kasir'),
                'role' => 'kasir'
            ],
        ]);
            
    }
}
