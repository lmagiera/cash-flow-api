<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\CashFlowAppTestCase;

class LoginPageTest extends CashFlowAppTestCase
{
    /**
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanLogin() {

        $this->browse(function (Browser $browser) {
            // TODO: Change autogenerated stub

            $user = $this->createUser();

            $browser
                ->visit(new LoginPage())
                ->enterUserCredentials($user)
                ->submitLogin()
                ->on(new HomePage())
                ->assertSee($user->name)
                ;


        });

    }



}