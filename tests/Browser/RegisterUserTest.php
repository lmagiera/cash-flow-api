<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RegisterPage;
use Tests\CashFlowAppTestCase;

class RegisterUserTest extends CashFlowAppTestCase
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
    public function testValidUserCanRegister()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->makeUser();

            $browser
                ->visit(new RegisterPage())
                ->registerUser($user)
                ->waitForText($user->name);
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testInvalidUserSeeValidationMessages()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->makeUser([
                'email'    => '',
                'name'     => '',
                'password' => '',

            ]);

            $browser
                ->visit(new RegisterPage())
                ->registerUser($user)
                ->waitForText('The name field is required.')
                ->waitForText('The email field is required.')
                ->waitForText('The password field is required.');
        });
    }
}
