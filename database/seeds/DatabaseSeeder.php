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
        ]);
            
        DB::table('test')->truncate();
        DB::table('test_detail')->truncate();
        for ($i=1; $i <= 10; $i++) { 
            DB::table('test')->insert([
                [
                    'int' => $i,
                    'str' => 'string ' . $i,
                    'bool' => 0,
                    'date' => '2019-10-10',
                    'id_unit' => rand(1,3)
                ]
            ]);
            for ($j=1; $j <= rand(1,3); $j++) {
                $detail = [
                    'id_test' => $i,
                    'enum' => 'enum_' . $j,
                    'decimal' => rand(99,999) / rand(1,5)
                ];
                if (rand(1,10) < 5) {
                    $detail['str_1'] = 'str_' . $j;
                }
                if (rand(1,10) > 5) {
                    $detail['str_2'] = 'str_' . $j;
                }
                DB::table('test_detail')->insert($detail);
            }
        }

        DB::table('unit')->truncate();
        DB::table('unit')->insert([
            [
                'nama' => 'pcs',
            ],
            [
                'nama' => 'kg',
            ],
            [
                'nama' => 'xxx',
            ],
        ]);
    }
}
