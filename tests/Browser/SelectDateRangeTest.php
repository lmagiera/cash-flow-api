<?php

namespace Tests\Browser;

use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class SelectDateRangeTest extends DuskTestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testSelectDateRange()
    {
        $this->browse(function (Browser $browser) {

            $from = Carbon::now()->startOfMonth()->format('Y-m-d');
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');

            $user = factory(User::class)->create();

            // add few transactions within tested dates

            $transactions = factory(Transaction::class, 2)->create([
                'planned_on' => function(array $t) use ($from) {
                    return (new Carbon($from))->addDays(rand(0,30));
                },
                'user_id' => $user->id
            ]);


            // add one transaction outside tested date range

            $transaction = factory(Transaction::class)->create([
                'planned_on' => (new Carbon($to))->addDays(2),
                'user_id' => $user->id
            ]);


            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->selectValidDateRange(['from' => $from, 'to' => $to])
                ->pause(2000)
                ->validateTransactionsOnList($transactions)
                ->screenshot('testSelectDateRange_'.date('YmdHis'))
                ->validateTransactionNotOnList($transaction)

                ;



        });
    }
}
