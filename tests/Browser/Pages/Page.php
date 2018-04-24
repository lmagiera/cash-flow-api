<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    public function url()
    {
        return env('APP_URL');
    }

    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@app'     => '#app',
            '@element' => '#selector',
        ];
    }
}
