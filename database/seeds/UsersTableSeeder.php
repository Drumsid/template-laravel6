<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => '1',
            'name' => 'Denis',
            'username' => 'admin',
            'email' => 'admin@mail.ru',
            'password' => bcrypt('rootadmin'),
        ]);

        DB::table('users')->insert([
            'role_id' => '2',
            'name' => 'Julia',
            'username' => 'author',
            'email' => 'author@mail.ru',
            'password' => bcrypt('rootauthor'),
        ]);
    }
}
