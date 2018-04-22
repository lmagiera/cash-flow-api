<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\LoginFormComponent;

class LoginPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return parent::url()."login";
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
        ];
    }

    public function enterUserCredentials(Browser $browser, User $user, $rememberMe = false) {

        $browser->within(new LoginFormComponent(), function (Browser $browser) use ($user, $rememberMe){

            $browser
                ->enterCredentials($user, $rememberMe)
            ;

        });

    }

    public function submitLogin(Browser $browser) {

        $browser->within(new LoginFormComponent(), function (Browser $browser) {

            $browser
                ->submit()
            ;

        });

    }
}
