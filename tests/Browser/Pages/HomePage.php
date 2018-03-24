<?php

namespace Tests\Browser\Pages;

use App\Transaction;
use Laravel\Dusk\Browser;

class HomePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return  parent::url().'home';
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
            '@input-amount-control' => 'input[id="transaction-amount"]',
            '@input-description-control' => 'input[id="transaction-description"]',
            '@input-planned-on-control' => 'input[id="transaction-planned-on"]',
            '@input-actual-on-control' => 'input[id="transaction-actual-on"]'
        ];
    }

    /**
     * @param Browser $browser
     * @return HomePage
     */
    function openNewTransactionModal(Browser $browser) {


        $browser
            ->assertSeeIn('@btn-add-transaction', 'Add New Transaction')

            ->click('@btn-add-transaction')
            ->waitFor('@modal-add-transaction')

            ->assertVisible('@modal-add-transaction')
            ->assertSeeIn('@modal-add-transaction', 'Add New Transaction')


            /* Check for all fields */

            // Transaction Description
            ->assertVisible('@input-description')
            ->assertSeeIn('@input-description', 'Transaction Description')
            //->assertVisible($this->)

            //Transaction Amount
            ->assertVisible('@input-amount')
            ->assertSeeIn('@input-amount', 'Amount')

            // Transaction Date Planned
            ->assertVisible('@input-planned-on')
            ->assertSeeIn('@input-planned-on', 'Date Planned')

            // Transaction Date Actual
            ->assertVisible('@input-actual-on')
            ->assertSeeIn('@input-actual-on', 'Date Actual')

            // Varying transaction
            ->assertVisible('@input-varying')
            ->assertSeeIn('@input-varying', 'Varying')

            ->assertVisible('@input-repeating')
            ->assertSeeIn('@input-repeating', 'Repeat')

            // confirm and cancel buttons

            ->assertVisible('@btn-save-transaction')
            ->assertSeeIn('@btn-save-transaction', 'Save')

            ->assertVisible('@btn-close-add-new-transaction')
            ->assertSeeIn('@btn-close-add-new-transaction', 'Cancel')

            ;



            return $this;



    }

    public function inputTransaction(Browser $browser, Transaction $t) {


        $screenShotName = 'screenshots/'.date("YmdHis").'.png';
        echo $screenShotName;

        $browser

            ->waitFor('@modal-add-transaction')
            ->assertVisible('@modal-add-transaction')

            ->type('@input-amount-control', $t->amount)
            ->assertVue('transaction.amount', $t->amount, '@tool-bar-component')

            ->type('@input-description-control', $t->description)
            ->assertVue('transaction.description', $t->description, '@tool-bar-component')

            ->type('@input-planned-on-control', $t->planned_on)
            ->assertVue('transaction.planned_on', $t->planned_on, '@tool-bar-component')

            ->type('@input-actual-on-control', $t->actual_on)
            ->assertVue('transaction.actual_on', $t->actual_on, '@tool-bar-component')

            ->select('@input-repeating-control', 3)
            ->assertVue('transaction.repeating_interval', 3, '@tool-bar-component')



           ->driver->takeScreenshot($screenShotName)


        ;




    }
}
