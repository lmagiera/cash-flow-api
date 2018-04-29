<?php

namespace Tests\Browser;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppUserLoggedInTestCase;

class SelectDateRangeTest extends CashFlowAppUserLoggedInTestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /**
     * A Dusk test example.
     *
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testSelectDateRange()
    {
        $this->browse(function (Browser $browser) {
            $from = Carbon::now()->startOfMonth()->format('Y-m-d');
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');

            // add few transactions within tested dates

            $transactions = [];

            $transactions[] = $this->createTransaction($this->currentUser, [
                'planned_on' => (new Carbon($from))->addDays(rand(0, 10))->format('Y-m-d'),
            ]);

            $transactions[] = $this->createTransaction($this->currentUser, [
                'planned_on' => (new Carbon($from))->addDays(rand(0, 10))->format('Y-m-d'),
            ]);

            // add one transaction outside tested date range

            $transaction = $this->createTransaction($this->currentUser, [
                'planned_on' => (new Carbon($to))->addDays(2)->format('Y-m-d'),
            ]);

            $browser
                ->visit(new HomePage())
                ->selectValidDateRange(['from' => $from, 'to' => $to])
                ->pause(500)
                ->validateTransactionsOnList(collect($transactions))
                ->validateTransactionNotOnList($transaction);
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testUserCanSelectAnyDateRange()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new HomePage())
                ->selectValidDateRange(['from' => '2019-02-28', 'to' => '2019-03-30']);
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testRunRandomDatesForDateSelector()
    {
        $this->browse(function (Browser $browser) {
            // TODO: Change autogenerated stub

            for ($c = 0; $c < 1; $c++) {
                $browser
                    ->visit(new HomePage());

                $yearStart = rand(2017, 2022);
                $monthStrat = rand(1, 12);
                $dayStart = rand(1, 28);
                $dateFrom = (new Carbon("$yearStart-$monthStrat-$dayStart"))->format('Y-m-d');
                $dateTo = (new Carbon($dateFrom))->addMonths(rand(0, 24))->addDays(rand(0, 50))->format('Y-m-d');

                echo "Running $c test: $dateFrom - $dateTo\n";

                $browser->selectValidDateRange(['from' => $dateFrom, 'to' => $dateTo]);
            }
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testTestSelectDates()
    {
        $this->browse(function (Browser $browser) {

            // TODO: Change autogenerated stub

            $dateFrom = '2018-04-19';
            $dateTo = '2018-05-02';

            $browser
                ->visit(new HomePage())
                ->selectValidDateRange(['from' => $dateFrom, 'to' => $dateTo]);
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testDatesHaveDefaultValuesOfCurrentMonth()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new HomePage())
                ->validateDateRangeIsOnCurrentMonth();
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testUserCanEnterDateRangeOnSmallScreens()
    {
        $this->browse(function (Browser $browser) {
            // TODO: Change autogenerated stub

            $browser
                ->resize(375, 667)
                ->visit(new HomePage())
                ->screenshot('test_screen_size')
                ->selectValidDateRange(['from' => '2018-01-01', 'to' => '2018-12-31']);
        });
    }
}
