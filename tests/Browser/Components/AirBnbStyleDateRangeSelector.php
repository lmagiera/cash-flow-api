<?php

namespace Tests\Browser\Components;

use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AirBnbStyleDateRangeSelector extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '.datepicker-trigger';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
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
            '@month-name' => '.asd__month-name',
            '@btn-next-month-control' => '.asd__change-month-button--next button',
            '@btn-prev-month-control' => '.asd__change-month-button--previous button',
            '@elem-date-range-selector-wrapper' => '.asd__wrapper',
            '@btn-open-date-range-selector' => '#datepicker-trigger',
            '@btn-apply-control' => 'div.asd__action-buttons > button:nth-child(2)'

        ];
    }

    public function selectDates(Browser $browser, array $dates) {

        $dateFrom = $dates['from'];
        $dateTo = $dates['to'];

        $currentDateRangeValue = $browser->value('@btn-open-date-range-selector');

        list($currentDateFrom, $currentDateTo) = explode(" | ", $currentDateRangeValue);

        if ( $currentDateFrom == $dateFrom && $currentDateTo == $dateTo) {
            // already set!
            return;
        }

        // opens date range dialog
        $browser->click('@btn-select-date-range-control');
        $browser->waitFor('@elem-date-range-selector-wrapper');
        $browser->screenshot('see-date-wrapper');

        // set starting value
        $monthDiffFrom = (new Carbon($currentDateFrom))->startOfMonth()->diffInMonths((new Carbon($dateFrom))->endOfMonth(), false);

        $moveFromForward = $monthDiffFrom > 0 ? true : false;
        $btnSelector = $moveFromForward ? "@btn-next-month-control": "@btn-prev-month-control";

        if ($moveFromForward) $monthDiffFrom -= 1;

        for ($c = 0; $c<=abs($monthDiffFrom); $c++) {
            $browser->click($btnSelector);
            $addMonths = $moveFromForward ? ($c + 1) : ($c + 1) * -1;
            $monthNow = (new Carbon($currentDateFrom))->addMonths($addMonths)->startOfMonth()->format("Y-m-d");
            $daySelector = '*[date="'.$monthNow.'"]';
            $browser->screenshot('select-from-before-wait');
            $browser->waitFor($daySelector);
        }

        // we have a date from on a right page
        $browser->screenshot('selected-from-before');

        $daySelector = '*[date="'.$dateFrom.'"]';

        $browser->click($daySelector);
        //$browser->screenshot('selected-from');

        $currentDateFrom = $dateFrom;

        // using date from, because we have already moved selectors
        $monthDiffTo = (new Carbon($currentDateFrom))->startOfMonth()->diffInMonths((new Carbon($dateTo))->endOfMonth(), false);

        $moveToForward = $monthDiffTo > 0 ? true : false;
        $btnSelector = $moveToForward ? "@btn-next-month-control": "@btn-prev-month-control";

        //if ($moveToForward) $monthDiffTo -= 1;

        for ($c = 0; $c < abs($monthDiffTo); $c++) {
            $browser->click($btnSelector);
            $addMonths = $moveToForward ? ($c + 1) : ($c + 1) * -1;
            $monthNow = (new Carbon($currentDateFrom))->addMonths($addMonths)->startOfMonth()->format("Y-m-d");
            $daySelector = '*[date="'.$monthNow.'"]';
            $browser->waitFor($daySelector);
        }

        $browser->screenshot('selected-to-before');

        // we have a date to on a right page
        $daySelector = '*[date="'.$dateTo.'"]';

        $browser->click($daySelector);
        $browser->screenshot('selected-to');

        $browser->click('@btn-apply-control');

        $browser->waitUntilMissing('.asd__wrapper--datepicker-open');

        $browser->pause(500);

        $browser->screenshot('selected-both');

        $browser->assertValue('@btn-open-date-range-selector', $dateFrom." | ".$dateTo);


    }
}
