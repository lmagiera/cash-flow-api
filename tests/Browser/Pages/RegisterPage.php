<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\RegisterPage\RegisterForm;

class RegisterPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return parent::url().'register';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     *
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

    /**
     * @param Browser $browser
     * @param User    $user
     *
     * @return void
     */
    public function registerUser(Browser $browser, User $user)
    {
        $browser->within(new RegisterForm(), function (Browser $browser) use ($user) {
            $browser
                ->setUser($user)
                ->submit();
        });
    }
}
