<?php

namespace Tests\Browser;

use Carbon\Carbon;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppUserLoggedInTestCase;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DisplayCashFlowDataTest extends CashFlowAppUserLoggedInTestCase
{

    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanSeeCashFlowData() {

        $this->browse(function (Browser $browser) {

            // create repeating transaction
            $transaction = $this->createTransaction($this->currentUser, [
               'repeating_interval' => 1,
                'amount' => 200
            ]);

            // create transaction in the past

            $pastTransaction = $this->createTransaction($this->currentUser, [
                'planned_on' => now()->startOfMonth()->subDays(1)->format('Y-m-d'),
                'amount' => 200
            ]);

            $cashFlowStart = now()->startOfMonth()->format('Y-m-d');
            $cashFlowAmount = $pastTransaction->amount;

            $cashFlowEnd = now()->endOfMonth()->format('Y-m-d');
            $cashFlowEndAmount = $cashFlowAmount + $transaction->amount;

            $browser

                ->visit(new HomePage())
                ->openCashFlowTab()

                ->assertVisible('@table-cash-flow')
                ->assertSee($cashFlowStart)
                ->assertSee($cashFlowAmount)

                ->assertSee($transaction->planned_on)
                ->assertSee($transaction->amount)

                ->assertSee($cashFlowEnd)
                ->assertSee($cashFlowEndAmount)




            ;

        });


    }

}
