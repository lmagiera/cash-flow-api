<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Browser\Pages\RegisterPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterUserTest extends DuskTestCase
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
    public function testValidUserCanRegister()
    {
        $this->browse(function (Browser $browser) {

            $user = factory(User::class)->make();

            $browser
                ->visit(new RegisterPage())
                ->registerValidUser($user)
            ;


        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testInvalidUserSeeValidationMessages() {

        $this->browse(function (Browser $browser) {

            $browser
                ->visit(new RegisterPage())
                ->registerInvalidUser()
            ;


        });


    }
}
