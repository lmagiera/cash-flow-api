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
            ->json('GET', 'api/transaction/' . $id);


        $response->assertStatus(200);
        //TODO: add other fields to test
        $response->assertJsonFragment(['amount' => $transaction->amount]);
        $response->assertJsonFragment(['repeating_interval' => $transaction->repeating_interval]);





    }

    public function testPostSimpleTransaction()
    {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->make(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/transaction', ['transaction' => $transaction]);

        $response->assertStatus(201);
        //TODO: add other fields to test
        $response->assertJsonFragment(['planned_on' => $transaction->planned_on]);
        $response->assertJsonFragment(['amount' => (string)$transaction->amount]);

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

        $response->assertStatus(201);

        $transactionData = $response->json('data');


        $response = $this
            ->actingAs($userOne, 'api')
            ->json('GET', '/api/transaction/'.$transactionData['id']);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $transactionData['id']]);

        $response = $this
            ->actingAs($userTwo, 'api')
            ->json('GET', '/api/transaction/'.$transactionData['id']);

        $response->assertStatus(404);





    }

    /**
     * Tests Cash Flow App Update Transaction API call
     *
     * Tests
     */
    public function testBasicUpdateTransaction() {

        $user = factory(User::class)->create();
        $transaction = factory(Transaction::class)->create([
            'user_id' => $user->id,
            'repeating_interval' => 1, // do not repeat,
        ]);

        $transaction->update_all = false;
        $transaction->amount = $transaction->amount + 200;


        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', '/api/transaction/'.$transaction->id, ['transaction' => $transaction])
        ;

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $transaction->id]);
        $response->assertJsonFragment(['amount' => $transaction->amount]);

    }

    public function testUserCanOnlyUpdateHisOwnTransaction() {

        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $transaction = factory(Transaction::class)->create([
            'user_id' => $userOne->id,
            'repeating_interval' => 0, // do not repeat,
        ]);


        $transaction->update_all = false;
        $transaction->amount = $transaction->amount + 200;

        $response = $this
            ->actingAs($userTwo, 'api')
            ->json('PUT', '/api/transaction/'.$transaction->id, ['transaction' => $transaction]);

        $response->assertNotFound();


        $response = $this
            ->actingAs($userOne, 'api')
            ->json('PUT', '/api/transaction/'.$transaction->id, ['transaction' => $transaction])
            ;

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $transaction->id]);
        $response->assertJsonFragment(['amount' => $transaction->amount ]);

    }

    public function testEditRepeatingTransactionUpdateAllFuture() {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->make([
            'user_id' => $user->id,
            'repeating_interval' => 1, // repeat monthly
        ]);

        $response =$this
            ->actingAs($user, 'api')
            ->json('POST', '/api/transaction', ['transaction' => $transaction]);

        $response->assertStatus(201);

        $transactionData = $response->json('data');

        $newAmount = sprintf("%0.2f", $transactionData['amount'] + 200);

        $transactionData['amount'] = $newAmount;
        $transactionData['update_all'] = true;

        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', '/api/transaction/'.$transactionData['id'], ['transaction' => $transactionData]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $transactionData['id']]);
        $response->assertJsonFragment(['amount' => $newAmount]);

        //FIXME: Following is exploiting the fact, that transaction.id is an autoincrement column
        // get next transaction,

        $response = $this->actingAs($user, 'api')->json('GET', '/api/transaction/'.($transactionData['id']+1));
        $response->assertStatus(200);

        $response->assertJsonFragment(['id' => ($transactionData['id'] + 1)]);
        $response->assertJsonFragment(['amount' => $newAmount]);

        //FIXME: Following is exploiting the fact, that transaction.id is an autoincrement column
        // get next transaction,

        $response = $this->actingAs($user, 'api')->json('GET', '/api/transaction/'.($transactionData['id']+2));
        $response->assertStatus(200);

        $response->assertJsonFragment(['id' => ($transactionData['id'] + 2)]);
        $response->assertJsonFragment(['amount' => $newAmount]);



    }

    public function testChangingRepeatingIntervalChangesAll() {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->make([
            'user_id' => $user->id,
            'repeating_interval' => 1, // repeat monthly
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/transaction', ['transaction' => $transaction]);

        $response->assertStatus(201);

        $transactionData = $response->json('data');

        $newAmount = sprintf("%0.2f", $transactionData['amount'] + 200);

        $transactionData['repeating_interval'] = 2;
        $transactionData['amount'] = $newAmount;
        $transactionData['update_all'] = true;

        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', '/api/transaction/'.$transactionData['id'], ['transaction' => $transactionData]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $transactionData['id']]);
        $response->assertJsonFragment(['amount' => $newAmount]);
        $response->assertJsonFragment(['planned_on' => $transactionData['planned_on']]);


        // FIXME: Following is exploiting the fact, that transaction.id is an autoincrement column, and we are entering 50 transactions
        // get next transaction,

        $response = $this->actingAs($user, 'api')->json('GET', '/api/transaction/'.($transactionData['id'] + 50));
        $response->assertStatus(200);

        $newDate = (new Carbon($transactionData['planned_on']))->addMonth(2)->format('Y-m-d');

        $response->assertJsonFragment(['id' => ($transactionData['id'] + 50)]);
        $response->assertJsonFragment(['amount' => $newAmount]);
        $response->assertJsonFragment(['planned_on' => $newDate]);


        // FIXME: Following is exploiting the fact, that transaction.id is an autoincrement column, and we are entering 50 transactions
        // get next transaction,

        $response = $this->actingAs($user, 'api')->json('GET', '/api/transaction/'.($transactionData['id'] + 51));
        $response->assertStatus(200);

        $newDate = (new Carbon($transactionData['planned_on']))->addMonth(4)->format('Y-m-d');

        $response->assertJsonFragment(['id' => ($transactionData['id'] + 51)]);
        $response->assertJsonFragment(['amount' => $newAmount]);
        $response->assertJsonFragment(['planned_on' => $newDate]);


    }

    public function testSettingRepeatingIntervalToZeroClearsOccurrences() {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->make([
            'user_id' => $user->id,
            'repeating_interval' => 1, // repeat monthly
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/transaction', ['transaction' => $transaction]);

        $response->assertStatus(201);

        $transactionData = $response->json('data');

        $newAmount = sprintf("%0.2f", $transactionData['amount'] + 200);

        $transactionData['repeating_interval'] = 0;
        $transactionData['amount'] = $newAmount;
        $transactionData['update_all'] = true;

        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', '/api/transaction/'.$transactionData['id'], ['transaction' => $transactionData]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $transactionData['id']]);
        $response->assertJsonFragment(['amount' => $newAmount]);
        $response->assertJsonFragment(['planned_on' => $transactionData['planned_on']]);

        $response = $this->actingAs($user, 'api')->json('GET', '/api/transaction/'.($transactionData['id'] + 50));
        $response->assertNotFound();


    }

    public function testDeleteSingleTransaction() {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->create([
            'user_id' => $user->id,
            'repeating_interval' => 0, // do not repeat,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', '/api/transaction/'.$transaction->id);

        $response->assertStatus(200);

        $transactionData = $response->json('data');

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/transaction/'.$transactionData['id']);

        $response->assertNotFound();



    }

    public function testDeletingRepeatingTransactionDeletesAll() {

        $user = factory(User::class)->create();

        $transaction = factory(Transaction::class)->make([
            'user_id' => $user->id,
            'repeating_interval' => 1, // do repeat,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/transaction', ['transaction' => $transaction]);

        $response->assertStatus(201);

        $transactionData = $response->json('data');

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', '/api/transaction/'.$transactionData['id']);

        $response->assertStatus(200);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/transaction/'.$transactionData['id']);

        $response->assertNotFound();

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', '/api/transaction/'.($transactionData['id'] + 1));

        $response->assertNotFound();

    }

    public function testDeleteOnlySingleTransactionFromRepetingSeries() {

        $this->markTestSkipped();

    }

    public function testGetCashFlowWithoutAnyArguments() {


        $user = factory(User::class)->create();

        // creates transaction starting prev month
        $transactionPrev = factory(Transaction::class)->create([
            'user_id' => $user->id,
            'repeating_interval' => 0, // do repeat,
            'planned_on' => Carbon::now()->subMonth(1)->format('Y-m-d')
        ]);

        // creates repeating transaction starting current month
        $transactionNow = factory(Transaction::class)->create([
            'user_id' => $user->id,
            'repeating_interval' => 1, // do repeat,
            'planned_on' => Carbon::now()->startOfMonth()->addDays(rand(0,28))->format('Y-m-d')
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/cashflow');

        $response->assertStatus(200);

        $response->assertJsonFragment(['data']);

        $response->assertJsonFragment(['cash_flow_start' => [
            'date' => Carbon::now()->startOfMonth()->subDay()->format('Y-m-d'),
            'amount' => currency($transactionPrev->amount)
        ]]);

        $response->assertJsonFragment(['cash_flow_data' => [[
            'date' => (new Carbon($transactionNow->planned_on))->format('Y-m-d'),
            'amount' => $transactionNow->amount,
            'saldo' => currency($transactionPrev->amount + $transactionNow->amount)
            ]
        ]]);

        $response->assertJsonFragment(['cash_flow_end' => [
            'date' => Carbon::now()->endOfMonth()->format('Y-m-d'),
            'amount' => currency($transactionPrev->amount + $transactionNow->amount)
        ]]);







    }
}
