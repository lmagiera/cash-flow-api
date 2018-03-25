<?php

namespace Tests\Browser;

use App\Transaction;
use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{

    protected function getUser($attributes = array()) {

        return factory(User::class)->create($attributes);

    }

    /**
     * A basic browser test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testBasicExample()
    {
        $user = $this->getUser();

        $this->browse(function (Browser $browser) use (&$user) {

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->assertSee($user->name);

        });

    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanAddValidTransaction() {


        $user = $this->getUser();

        $transaction = factory(Transaction::class)->make();

        $this->browse(function (Browser $b) use ($user, $transaction) {

            $fpScreenshots = storage_path('framework/testing/screenshots'.date("YmdHis").'.png');

            echo "Screen Shot Path: ".$fpScreenshots, "\n";

            $b->loginAs($user)
                ->visit(new HomePage())
                ->openNewTransactionModal()
                ->inputTransaction($transaction)
                ->saveNewTransaction()
            ;

            $b->driver->takeScreenshot($fpScreenshots);


        });


    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserSeesValidationErrors() {

        $user = $this->getUser();

        $this->browse(function (Browser $browser) use ($user) {

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->openNewTransactionModal()
                ->saveInvalidTransaction();




        });

    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserSeeHisTransactionList() {


        $user = $this->getUser();

        $this->browse(function(Browser $browser) use ($user) {

            $browser
                ->loginAs($user)
                ->visit(new HomePage())
                ->browseTransationList()

                ;

        });

    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserSeesTransactionAdded() {

        $user = $this->getUser();
        $transaction = factory(Transaction::class)->make();

        $this->browse(function (Browser $browser) use ($user, $transaction) {

            $browser->loginAs($user)
                ->visit(new HomePage())
                ->openNewTransactionModal()
                ->inputTransaction($transaction)
                ->saveNewTransaction()
                ->validateTransactionOnList($transaction)

                ;

        });

    }





}
