<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\WelcomePage;
use Tests\CashFlowAppTestCase;

class LoginAndRememberUserTest extends CashFlowAppTestCase
{
    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @return void
     */
    public function testUserIsRemembered()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->createUser();

            $browser
                ->visit(new LoginPage())
                ->enterUserCredentials($user, true)
                ->submitLogin()
                ->on(new HomePage());

            session()->invalidate();

            $this->browse(function (Browser $browser) {
                $browser
                    ->visit(new WelcomePage())
                    ->assertSeeLink('Home');
            });
        });
    }
}
