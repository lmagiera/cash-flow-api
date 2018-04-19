<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppTestCase;

class EditTransactionTest extends CashFlowAppTestCase
{

    use RefreshDatabase, DatabaseMigrations;

    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanDeleteSingleTransaction()
    {
        $this->browse(function (Browser $browser) {

            $user = $this->createUser();

            $transaction = $this->makeTransaction(['repeating_interval' => 0]);

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->saveTransaction($transaction)
                ->deleteSingleTransaction($transaction)

            ;
        });
    }
}
