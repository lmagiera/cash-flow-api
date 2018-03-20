<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create([
           'id' => 1,
           'name' => 'One And Only',
           'email' => 'lmagiera@gmail.com'
        ]);
    }
}
