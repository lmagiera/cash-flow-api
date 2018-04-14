<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\WelcomePage;
use Tests\DuskTestCase;

class WelcomePageTest extends DuskTestCase
{
    /**
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


    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testLoginLinkVisible() {

        $this->browse(function (Browser $browser) {

            $browser
                ->visit(new WelcomePage())
                ->assertSeeLink("Login")
            ;

        });

    }

    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testRegisterLoginLinkVisible() {

        $this->browse(function (Browser $browser) {
            // TODO: apply testRegisterLoginLinkVisible test
            $browser
                ->visit(new WelcomePage())
                ->assertSeeLink("Register")
                ;
        });


    }




}
