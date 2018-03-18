<?php

namespace Tests\Feature;

use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testTransactionApiGetExists() {

        $response = $this->json('GET', '/api/transaction');

        $response->assertStatus(200);


    }

    public function testTransactionListReturnsJson() {

        $response = $this->json('GET', '/api/transaction');

        $response->assertStatus(200);

        $response->assertJson([['test' => 1], ['test' => 2]]);

    }

    public function testTransactionWithGuard() {

        // create user first
        $user = factory(User::class, 1)
            ->create()
            ->each(function (User $u) {

                $transactions = factory(Transaction::class, 10)->create(['user_id' => $u->id]);
                $u->transactions()->saveMany($transactions);
        })->first();

        $response = $this->actingAs($user, 'api')
            ->withSession([])
            ->json('GET', '/api/transaction');


        $response->assertStatus(200);



    }
}
