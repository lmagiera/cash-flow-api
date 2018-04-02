<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TestDataSeeder;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{

    use DatabaseMigrations;
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->runDatabaseMigrations();

        $this->seed(TestDataSeeder::class);
    }

    /**
     *
     */
    public function testGetTransactions()
    {

        $intRandomNoOfTransactions = rand(10, 20);

        $user = factory(User::class)->create();

        // we have one and only user now
        factory(Transaction::class, $intRandomNoOfTransactions)->create([
            'user_id' => $user->id,
            'planned_on' => function () {
                return Carbon::now()->startOfMonth()->addDays(rand(0, 30));
        }]);

        // make a call
        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/transaction');

        // check response
        $response->assertStatus(200);
        $response->assertJsonCount($intRandomNoOfTransactions, 'data');


    }

    //TODO: rename this method
    //TODO: add php doc
    public function testGetTransactionsBetweenValidDates()
    {

        $intRandomNoOfTransactions = rand(10, 20);

        $from = Carbon::now()->startOfMonth();
        $to = Carbon::now()->endOfMonth();

        $user = factory(User::class)->create();

        // we have one and only user now
        factory(Transaction::class, $intRandomNoOfTransactions)->create([
            'user_id' => $user->id,
            'planned_on' => function () {
                return Carbon::now()->startOfMonth()->addDays(rand(0, 25));
        }]);

        // make a call
        $response = $this
            ->actingAs($user, 'api')
            ->json('GET',
            "/api/transaction?from={$from->format('Y-m-d')}&to={$to->format('Y-m-d')}");


        // check response
        $response->assertStatus(200);
        $response->assertJsonCount($intRandomNoOfTransactions, 'data');


    }

    public function testGetTransactionListBetweenInvalidDates() {

        $user = factory(User::class)->create();


        $response = $this
            ->actingAs($user, 'api')
            ->json('GET',
            "/api/transaction?from=2018-03-20&to=2017-01-01");


        $response->assertStatus(422);
        $response->assertJsonFragment(['message']);
        $response->assertJsonFragment(['errors']);
        $response->assertJsonFragment(['from']);
        $response->assertJsonFragment(['to']);



    }

    /**
     * @fixes CFA-18
     */
    public function testGetTransactionFromSameDates() {

        $user = factory(User::class)->create();
        $transaction = factory(Transaction::class)->create([
            'planned_on' => '2018-03-01',
            'user_id' => $user->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET',
                "/api/transaction?from=2018-03-01&to=2018-03-01");

        $response->assertStatus(200);


        //TODO: add other fields to test
        $response->assertJsonFragment(['id' => $transaction->id]);
        $response->assertJsonFragment(['amount' => (string)$transaction->amount]);



    }

    public function testGetSingleTransaction()
    {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->create(['user_id' => $user->id]);
        $id = $transaction->id;

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/transaction/' . $id);


        $response->assertStatus(200);


        //TODO: add other fields to test
        $response->assertJson(['data' => [
            'id' => $id,
            'amount' => $transaction->amount
        ]]);


    }

    public function testPostSimpleTransaction()
    {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->make(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/transaction', ['transaction' => $transaction]);

        $response->assertStatus(200);

    }

    public function testPostTransactionValidation()
    {

        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/transaction', []); // send empty request

        $response->assertStatus(422); // expect validation errors

        $response->assertJsonValidationErrors(['transaction']);


    }

    public function testUserSeesOnlyHisTransactions() {


        $userOne = factory(User::class)->create();
        $transactionOne = factory(Transaction::class)->create(['user_id' => $userOne->id]);

        $userTwo = factory(User::class)->create();
        $transactionTwo = factory(Transaction::class)->create(['user_id' => $userTwo->id]);

        $response = $this
            ->actingAs($userOne, 'api')
            ->json('GET', '/api/transaction/'.$transactionTwo->id);

        $response->assertStatus(404);

        $response = $this
            ->actingAs($userTwo, 'api')
            ->json('GET', '/api/transaction/'.$transactionOne->id);

        $response->assertStatus(404);


    }

    public function testUserCanOnlyAddTransactionToHisOwnAccount() {

        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $transactionOne = factory(Transaction::class)->make(['user_id' => $userTwo->id]); // intentionally wrong user ID

        $response = $this
            ->actingAs($userOne, 'api')
            ->json('POST', '/api/transaction', ['transaction' => $transactionOne]);

        $response->assertStatus(200);

        $response = $this
            ->actingAs($userOne, 'api')
            ->json('GET', '/api/transaction/'.$transactionOne->id);

        $response->assertStatus(200);

        $response = $this
            ->actingAs($userTwo, 'api')
            ->json('GET', '/api/transaction/'.$transactionOne->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['data' => []]);




    }
}
