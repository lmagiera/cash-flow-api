<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class NavigationBar extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#app .navbar';
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
            '@text-app-name'     => '.navbar-brand',
            '@nav-profile-links' => '#navbarDropdown',
        ];
    }

    public function openProfileDropDown(Browser $browser)
    {
        $browser->click('@nav-profile-links');
    }

    public function logout(Browser $browser)
    {
        $browser->openProfileDropDown();
        $browser->clickLink('Logout');
    }
}
