<?php

namespace Tests\Browser\Pages;

use App\Transaction;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Support\Collection;
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
            '@input-actual-on-control' => 'input[id="transaction-actual-on"]',
            '@modal-add-transaction-title' => '.modal-title',
            '@dialog-remove-transaction' => '.message-box',
            //'@dialog-btn-yes-control' => '#btn-yes-control'
        ];
    }


    public function browseTransactionList(Browser $browser) {


        $browser
            ->assertVisible('@tab-nav-transaction-list')
            ->assertVisible('@table-transaction-list')

        ;




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
            //->assertSeeIn('@modal-add-transaction-title', 'Add New Transaction')


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

            //->assertVisible('@input-actual-on')
            //->assertSeeIn('@input-actual-on', 'Date Actual')

            // Varying transaction
            //->assertVisible('@input-varying')
            //->assertSeeIn('@input-varying', 'Varying')

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

        $browser

            ->waitFor('@modal-add-transaction')
            ->assertVisible('@modal-add-transaction')

            ->type('@input-amount-control', $t->amount)
            ->assertVue('transaction.amount', $t->amount, '@tool-bar-component')

            ->type('@input-description-control', $t->description)
            ->assertVue('transaction.description', $t->description, '@tool-bar-component')

            ->type('@input-planned-on-control', $t->planned_on)
            ->assertVue('transaction.planned_on', $t->planned_on, '@tool-bar-component')

            //->type('@input-actual-on-control', $t->actual_on)
            //->assertVue('transaction.actual_on', $t->actual_on, '@tool-bar-component')

            ->select('@input-repeating-control', $t->repeating_interval)
            ->assertVue('transaction.repeating_interval', $t->repeating_interval, '@tool-bar-component')


        ;




    }

    public function saveInvalidTransaction(Browser $browser) {

        $browser

            ->waitFor('@modal-add-transaction')
            ->assertVisible('@modal-add-transaction')
            ->select('@input-repeating-control', 4) // some invalid value

            ->click('@btn-save-transaction')

            ->waitFor('@alert-invalid-transaction')

            ->assertVisible('@feedback-invalid-description')
            ->assertVisible('@feedback-invalid-amount')
            ->assertVisible('@feedback-invalid-planned-on')
            ->assertVisible('@feedback-invalid-repeating-interval')

            ->assertVue('errors["transaction.description"][0]',
                'The transaction.description field is required.', '@tool-bar-component')
            ->assertVue('errors["transaction.amount"][0]',
                'The selected transaction.amount is invalid.', '@tool-bar-component')
            ->assertVue('errors["transaction.planned_on"][0]',
                'The transaction.planned on field is required.', '@tool-bar-component')
            ->assertVue('errors["transaction.repeating_interval"][0]',
                'The selected transaction.repeating interval is invalid.', '@tool-bar-component')
            ;


    }

    public function attemptSaveTransaction(Browser $browser) {

        $browser
            ->waitFor('@modal-add-transaction')
            ->assertVisible('@modal-add-transaction')

            ->click('@btn-save-transaction')


        ;

    }



    public function saveNewTransaction(Browser $browser) {

        $browser
            ->waitFor('@modal-add-transaction')
            ->assertVisible('@modal-add-transaction')

            ->click('@btn-save-transaction')
            ->waitUntilMissing('@modal-add-transaction')
            ->pause(1000) // wait for server call

        ;



    }


    public function validateTransactionOnList(Browser $browser, Transaction $transaction) {


        $browser
            ->assertSeeIn('@table-transaction-list', $transaction->description)
            ->assertSeeIn('@table-transaction-list', $transaction->amount)
            ->assertSeeIn('@table-transaction-list', $transaction->planned_on)
            ;




    }

    public function validateTransactionNotOnList(Browser $browser, Transaction $transaction) {

        $browser
            //->assertDontSeeIn('@table-transaction-list', $transaction->description)
            //->assertDontSeeIn('@table-transaction-list', $transaction->amount)
            ->assertDontSeeIn('@table-transaction-list', $transaction->planned_on)
        ;


    }

    public function validateTransactionsOnList(Browser $browser, Collection $transactions) {

        $transactions->each(function ($transaction) use ($browser) {

            $this->validateTransactionOnList($browser, $transaction);

        });

    }

    public function selectValidDateRange(Browser $browser, array $dates) {


        $browser

            ->assertVisible('@input-date-from-control')
            ->assertVisible('@input-date-to-control')

            //->assertVisible('@btn-apply-control')

            ->clear('@input-date-from-control')
            //->screenshot('001-clear-from')
            ->type('@input-date-from-control', $dates['from'])
            //->screenshot('002-type-from')
            ->keys('@input-date-to-control', '{ENTER}', '{TAB}')
            //->screenshot('003-keys-from')


            //->screenshot('test-1')

            ->clear('@input-date-to-control')
            ->type('@input-date-to-control', $dates['to'])
            ->keys('@input-date-to-control', '{ENTER}', '{TAB}')

            //->screenshot('test-2')


            ->assertMissing('@feedback-invalid-dates')


            ->assertVue('from', $dates['from'], '@date-range-selector-component')
            ->assertVue('to', $dates['to'], '@date-range-selector-component')

        ;



    }

    public function validateDateRangeIsOnCurrentMonth(Browser $browser) {

        $from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $to = Carbon::now()->endOfMonth()->format('Y-m-d');

        $browser

            ->assertVue('from', $from, '@date-range-selector-component')
            ->assertVue('to', $to, '@date-range-selector-component')

            ->assertValue( '@input-date-from-control', $from)
            ->assertValue('@input-date-to-control', $to)

            ;


    }


    public function saveTransaction(Browser $browser, Transaction $transaction) {
        
        $this->openNewTransactionModal($browser);
        $this->inputTransaction($browser, $transaction);
        $this->saveNewTransaction($browser);
        $browser->pause(1500);
        $this->validateTransactionOnList($browser, $transaction);


    }

    public function deleteSingleTransaction(Browser $browser, Transaction $transaction) {

        $this->validateTransactionOnList($browser, $transaction);

        $browser
            ->click('@btn-remove-transaction-control')
            ->waitFor('@dialog-remove-transaction')
            ->within("@dialog-remove-transaction", function (Browser $browser){

                $element = WebDriverBy::cssSelector('#btn-yes-control');
                $browser->driver->wait()->until(WebDriverExpectedCondition::presenceOfElementLocated($element));
                $browser->pause(1000);
                $browser->driver->findElement($element)->click();
                $browser
                    ->waitUntilMissing('@dialog-remove-transaction')
                ;
            })
            ->pause(1000)
            ->validateTransactionNotOnList($transaction)
        ;

    }

    public function deleteRepeatingTransaction(Browser $browser, Transaction $transaction) {

        $this->validateTransactionOnList($browser, $transaction);

        $browser
            ->click('@btn-remove-transaction-control')
            ->waitFor('@dialog-remove-transaction')
            ->within("@dialog-remove-transaction", function (Browser $browser){

                //$browser->screenshot('100-dialog');

                $element = WebDriverBy::cssSelector('#btn-yes-control');
                $browser->driver->wait()->until(WebDriverExpectedCondition::presenceOfElementLocated($element));
                $browser->pause(1000);
                $browser->driver->findElement($element)->click();
                //$browser->screenshot('101-dialog');
                $browser
                    ->waitUntilMissing('@dialog-remove-transaction')
                ;
            })
            ->pause(1000)
            ->validateTransactionNotOnList($transaction);


    }

    public function deleteSingleRepeatingTransaction(Browser $browser, Transaction $transaction) {


        $this->validateTransactionOnList($browser, $transaction);

        $browser
            ->click('@btn-remove-transaction-control')
            ->waitFor('@dialog-remove-transaction')
            ->within("@dialog-remove-transaction", function (Browser $browser){

                //$browser->screenshot('100-dialog');

                $element = WebDriverBy::cssSelector('#btn-no-control');
                $browser->driver->wait()->until(WebDriverExpectedCondition::presenceOfElementLocated($element));
                $browser->pause(1000);
                $browser->driver->findElement($element)->click();
                //$browser->screenshot('101-dialog');
                $browser
                    ->waitUntilMissing('@dialog-remove-transaction')
                ;
            })
            ->pause(1000)
            ->validateTransactionNotOnList($transaction);


    }

}
