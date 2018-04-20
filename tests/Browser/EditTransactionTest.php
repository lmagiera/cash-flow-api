<?php

namespace Tests\Browser;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppUserLoggedInTestCase;

class EditTransactionTest extends CashFlowAppUserLoggedInTestCase
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
                ->visit(new HomePage())
                ->saveTransaction($transaction)
                ->deleteSingleTransaction($transaction)

            ;
        });
    }

    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanDeleteRepeatingTransaction() {

        $this->browse(function (Browser $browser) {

            $transaction = $this->makeTransaction(['repeating_interval' => 1]);

            $browser
                ->visit(new HomePage())
                ->saveTransaction($transaction)
                ->pause(500)
                //->screenshot('test-0')
                ->deleteRepeatingTransaction($transaction)
            ;

            $browser->validateTransactionNotOnList($transaction);

            $dateFrom = Carbon::now()->startOfMonth()->addMonth()->format('Y-m-d');
            $dateTo = (new Carbon($dateFrom))->endOfMonth()->format('Y-m-d');

            $transaction->planned_on = (new Carbon($transaction->planned_on))->addMonth()->format('Y-m-d');

            $browser
                ->selectValidDateRange(['from' => $dateFrom, 'to' => $dateTo])
                ->validateTransactionNotOnList($transaction);

            ;

        });


    }

    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanDeleteSingleRepeatingTransaction() {

        $this->browse(function (Browser $browser) {
            // TODO: Change autogenerated stub


            $transaction = $this->makeTransaction(['repeating_interval' => 1]);

            $browser
                ->visit(new HomePage())
                ->saveTransaction($transaction)
                ->pause(500)
                //->screenshot('test-0')
                ->deleteSingleRepeatingTransaction($transaction)
            ;

            $browser->validateTransactionNotOnList($transaction);

            $dateFrom = Carbon::now()->startOfMonth()->addMonth()->format('Y-m-d');
            $dateTo = (new Carbon($dateFrom))->endOfMonth()->format('Y-m-d');

            $transaction->planned_on = (new Carbon($transaction->planned_on))->addMonth()->format('Y-m-d');

            $browser
                ->selectValidDateRange(['from' => $dateFrom, 'to' => $dateTo])
                ->validateTransactionOnList($transaction);

        });


    }
}
