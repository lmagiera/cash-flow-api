<?php

namespace Tests\Browser;

use Tests\Browser\Pages\WelcomePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WelcomePageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testWelcomePage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new WelcomePage())
                ->assertTitle(env('APP_NAME'))
                ->assertSee(env('APP_NAME'))
            ;

        });
    }
}
