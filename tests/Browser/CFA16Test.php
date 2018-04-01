<?php

namespace Tests\Browser;

use App\Transaction;
use App\User;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CFA16Test extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testCFA16()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(User::class)->create();

            $transaction = factory(Transaction::class)->make([
                'user_id' => $user->id
            ]);
            
            
            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->saveTransaction($transaction)
                ->openNewTransactionModal()
                ->assertValue('@input-description-control', '')
                ->assertValue('@input-amount-control', '0')
                ->assertValue('@input-planned-on-control', '')

                ;

            
        });
    }
}
