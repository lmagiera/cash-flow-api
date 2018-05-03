<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppUserLoggedInTestCase;

class HomeLinkTest extends CashFlowAppUserLoggedInTestCase
{
    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanSeeLoginLink() {

        $this->browse(function (Browser $browser) {

            $browser->visit("/")
                ->assertSee('HOME')
                ->clickLink("Home")
                ->on(new HomePage())
                ;

        });


    }
}
