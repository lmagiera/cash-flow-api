<?php

namespace Tests\Browser;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SelectDateRangeNavigationTest extends DuskTestCase
{

    use RefreshDatabase, DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserSeeNextDateRangeButton()
    {
        $this->browse(function (Browser $browser) {


            $user = factory(User::class)->create();

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->assertVisible('@btn-date-range-next-control');
            ;

        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserSeePrevDateRangeButton()
    {
        $this->browse(function (Browser $browser) {


            $user = factory(User::class)->create();

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->assertVisible('@btn-date-range-prev-control');
            ;

        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testCanMoveMonthAhead() {

        $this->browse(function (Browser $browser) {


            $user = factory(User::class)->create();

            $from = Carbon::now()->startOfMonth()->format('Y-m-d');
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');

            $newFrom = Carbon::now()->startOfMonth()->addMonth(1)->format('Y-m-d');
            $newTo = (new Carbon($newFrom))->endOfMonth()->format('Y-m-d');

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->selectValidDateRange(['from' => $from, 'to' => $to])
                ->click('@btn-date-range-next-control')
                ->assertVue('from', $newFrom, '@date-range-selector-component')
                ->assertVue('to', $newTo, '@date-range-selector-component')
            ;

        });

    }

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testCanMoveMonthBehind() {

        $this->browse(function (Browser $browser) {


            $user = factory(User::class)->create();

            $from = Carbon::now()->startOfMonth()->format('Y-m-d');
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');

            $newFrom = Carbon::now()->startOfMonth()->subMonth(1)->format('Y-m-d');
            $newTo = (new Carbon($newFrom))->endOfMonth()->format('Y-m-d');

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->selectValidDateRange(['from' => $from, 'to' => $to])
                ->click('@btn-date-range-prev-control')
                ->assertVue('from', $newFrom, '@date-range-selector-component')
                ->assertVue('to', $newTo, '@date-range-selector-component')
            ;

        });

    }

}
