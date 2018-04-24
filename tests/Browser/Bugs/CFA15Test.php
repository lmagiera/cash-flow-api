<?php

namespace Tests\Browser\Bugs;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppTestCase;

class CFA15Test extends CashFlowAppTestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /**
     * CFA-15 Adding new transaction with non-numeric value does not validate and show error.
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testCFA15()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUser();
            $t = $this->makeTransaction(['amount' => '!invalid']);

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->openNewTransactionModal()
                ->inputTransaction($t)
                ->attemptSaveTransaction()
                ->waitFor('@feedback-invalid-amount');
        });
    }
}
