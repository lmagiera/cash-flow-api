<?php

namespace Tests\Browser;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class EditTransactionTest extends DuskTestCase
{

    use RefreshDatabase, DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanSeeEditAndDeleteButtons()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(User::class)->create();

            $transaction = factory(Transaction::class)->make([
                'repeating_interval' => 0,
                'user_id' => $user->id
            ]);

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->saveTransaction($transaction)
                ->deleteTransaction($transaction)
            ;
        });
    }
}
