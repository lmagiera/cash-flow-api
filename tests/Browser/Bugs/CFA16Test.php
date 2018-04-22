<?php

namespace Tests\Browser\Bugs;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppTestCase;

class CFA16Test extends CashFlowAppTestCase
{


    use DatabaseMigrations, RefreshDatabase;


    /**
     * CFA-16 After saving new transaction and creating new one modal box shows data from old transaction
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testCFA16()
    {
        $this->browse(function (Browser $browser) {


            $user = $this->createUser();
            $transaction = $this->makeTransaction();

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
