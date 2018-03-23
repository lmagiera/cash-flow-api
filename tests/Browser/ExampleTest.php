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
    public function testUserCanClickAddTransactionButton() {


        $user = $this->getUser();

        $transaction = factory(Transaction::class)->make();

        $this->browse(function (Browser $b) use ($user, $transaction) {

            $b->loginAs($user)
                ->visit(new HomePage())
                ->openNewTransactionModal()
                ->inputTransaction($transaction)
            ;


        });


    }
}
