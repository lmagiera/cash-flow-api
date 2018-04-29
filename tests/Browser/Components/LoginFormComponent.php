<?php

namespace Tests\Browser\Components;

use App\User;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class LoginFormComponent extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#form-user-login';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element'                 => '#selector',
            '@input-user-name-control' => '#email',
            '@input-user-password'     => '#password',
            '@btn-login-control'       => '#btn-login',
            '@chk-remember-me-control' => '#checkbox-remember-me',

        ];
    }

    public function enterCredentials(Browser $browser, User $user, $rememberMe = false)
    {
        $browser
            ->value('@input-user-name-control', $user->email)
            ->value('@input-user-password', 'secret');

        if ($rememberMe) {
            $browser->check('@chk-remember-me-control');
        }
    }

    public function submit(Browser $browser)
    {
        $browser
            ->click('@btn-login-control');
    }
}
