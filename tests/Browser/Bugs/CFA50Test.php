<?php

namespace Tests\Browser\Bugs;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppUserLoggedInTestCase;

class CFA50Test extends CashFlowAppUserLoggedInTestCase
{
    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testCFA50()
    {
        $this->browse(function (Browser $browser) {
            $this->createTransaction($this->currentUser, [
                'planned_on' => now()->startOfMonth()->subMonth()->addDays(rand(1, 15))->format('Y-m-d'),
            ]);

            $this->createTransaction($this->currentUser);

            $browser->visit(new HomePage())
                ->openCashFLowTab()
                ->waitFor('@graph-cash-flow')
                //TODO: don't really know how to test displayed graph
;
        });
    }
}
