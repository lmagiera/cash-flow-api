<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\CashFlowAppUserLoggedInTestCase;

class HomeLinkTest extends CashFlowAppUserLoggedInTestCase
{
    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testUserCanSeeLoginLink()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('HOME')
                ->clickLink('Home')
                ->on(new HomePage());
        });
    }
}
