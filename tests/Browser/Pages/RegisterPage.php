<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class RegisterPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return parent::url().'/register';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertUrlIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [

            '@element' => '#selector',
            '@input-user-name-control' => '#name',
            '@input-user-email-control' => '#email',
            '@input-user-password-control' => '#password',
            '@input-user-password-confirm-control' => '#password-confirm',
            '@btn-register-control' => '#btn-register-submit'

        ];
    }

    public function registerValidUser(Browser $browser, User $user) {

        $browser
            ->type('@input-user-name-control', $user->name)
            ->type('@input-user-email-control', $user->email)
            ->type('@input-user-password-control', 'secret')
            ->type('@input-user-password-confirm-control', 'secret')



            ->click('@btn-register-control')
            ->waitForText($user->name)


        ;


    }

    public function registerInvalidUser(Browser $browser) {

        $browser
            ->click('@btn-register-control')
            ->screenshot('register-'.date('YmdHis'))
            ->waitForText('The name field is required.')
            ->waitForText('The email field is required.')
            ->waitForText('The password field is required.')

            ;

    }
}
