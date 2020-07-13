<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Juan',
            'email'=> 'hola@dominio.com',
            'password' => bcrypt('123')
        ]);

        User::create([
            'name' => 'Maria',
            'email'=> 'hola2@dominio.com',
            'password' => bcrypt('123')
        ]);
    }
}
