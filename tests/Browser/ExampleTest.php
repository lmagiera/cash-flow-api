<?php

namespace Tests\Browser;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use TestDataSeeder;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->seed(TestDataSeeder::class);
    }



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
                ->browseTransactionList()

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

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testUserCanOnlyAddTransactionToHisAccount() {


        $firstUser = $this->getUser();
        $firstTransaction = factory(Transaction::class)->make();

        $this->browse(function (Browser $browser) use ($firstUser, $firstTransaction) {

          $browser
              ->loginAs($firstUser)
              ->visit(new HomePage())
              ->openNewTransactionModal()
              ->inputTransaction($firstTransaction)
              ->saveNewTransaction()
              ->validateTransactionOnList($firstTransaction)
              ;

        });

        $secondUser = $this->getUser();
        $secondTransaction = factory(Transaction::class)->make();

        $this->browse(function (Browser $browser) use ($secondUser, $secondTransaction, $firstTransaction) {

            $browser
                ->loginAs($secondUser)
                ->visit(new HomePage())
                ->openNewTransactionModal()
                ->inputTransaction($secondTransaction)
                ->saveNewTransaction()
                ->validateTransactionOnList($secondTransaction)
                ;
            $browser->assertDontSee($firstTransaction->description);

        });

        $this->browse(function (Browser $browser) use ($firstUser, $secondTransaction) {

            $browser
                ->loginAs($firstUser)
                ->visit(new HomePage())
                ->assertDontSee($secondTransaction->description)
                ;

        });



    }





}
