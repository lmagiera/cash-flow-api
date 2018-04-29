<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\WelcomePage;
use Tests\DuskTestCase;

class WelcomePageTest extends DuskTestCase
{
    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testWelcomePage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new WelcomePage())
                ->assertTitle(env('APP_NAME'))
                ->assertSee(env('APP_NAME'));
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testLoginLinkVisible()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new WelcomePage())
                ->assertSeeLink('Login');
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testRegisterLoginLinkVisible()
    {
        $this->browse(function (Browser $browser) {
            // TODO: apply testRegisterLoginLinkVisible test
            $browser
                ->visit(new WelcomePage())
                ->assertSeeLink('Register');
        });
    }
}
