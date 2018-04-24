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
        $user = factory(User::class, 1)->create([
           'id'    => 1,
           'name'  => 'One And Only',
           'email' => 'lmagiera@gmail.com',
        ]);

        //dd($user);

        $date = \Carbon\Carbon::now()->startOfMonth()->addDays(rand(0, 20));

        factory(\App\Transaction::class)->create([
            'user_id'            => 1,
            'repeating_interval' => 0,
            'planned_on'         => $date,
        ]);

        /*

        $uuid = \Ramsey\Uuid\Uuid::uuid4();
        $date = \Carbon\Carbon::now()->startOfMonth()->addDays(rand(0,20));

        for ( $c=0; $c<50; $c++) {

            factory(\App\Transaction::class)->create([
                'user_id' => 1,
                'repeating_id' => $uuid,
                'repeating_interval' => 1,
                'planned_on' => $date
            ]);

            $date = (new \Carbon\Carbon($date))->addMonth(1);

        }

        $uuid = \Ramsey\Uuid\Uuid::uuid4();
        $date = \Carbon\Carbon::now()->startOfMonth()->addDays(rand(0,20));

        for ( $c=0; $c<50; $c++) {

            factory(\App\Transaction::class)->create([
                'user_id' => 1,
                'repeating_id' => $uuid,
                'repeating_interval' => 2,
                'planned_on' => $date
            ]);

            $date = (new \Carbon\Carbon($date))->addMonth(2);

        }

        $uuid = \Ramsey\Uuid\Uuid::uuid4();
        $date = \Carbon\Carbon::now()->startOfMonth()->addDays(rand(0,20));

        for ( $c=0; $c<50; $c++) {

            factory(\App\Transaction::class)->create([
                'user_id' => 1,
                'repeating_id' => $uuid,
                'repeating_interval' => 3,
                'planned_on' => $date
            ]);

            $date = (new \Carbon\Carbon($date))->addMonth(3);

        }

        */
    }
}
