<?php

namespace Tests\Browser\Components\RegisterPage;

use App\User;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class RegisterForm extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '@form-register-user';
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

        foreach ($this->elements() as $element) {
            $browser->assertVisible($element);
        }
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@input-user-name-control'             => '#name',
            '@input-user-email-control'            => '#email',
            '@input-user-password-control'         => '#password',
            '@input-user-password-confirm-control' => '#password-confirm',
            '@btn-register-control'                => '#btn-register-submit',
        ];
    }

    public function setUser(Browser $browser, User $user)
    {
        $browser
            ->type('@input-user-name-control', $user->name)
            ->type('@input-user-email-control', $user->email)
            ->type('@input-user-password-control', $user->password)
            ->type('@input-user-password-confirm-control', $user->password);
    }

    public function submit(Browser $browser)
    {
        $browser->click('@btn-register-control');
    }
}
