<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $user = factory(\App\User::class, 1)
            ->create()
            ->each(function (\App\User $u) {

                $transactions = factory(\App\Transaction::class, 10)->create(['user_id' => $u->id]);
                $u->transactions()->saveMany($transactions);
        })->first();

        $response = $this->actingAs($user, 'api')
            ->withSession([])
            ->json('GET', '/api/transaction');

        dd($response);

        $response->assertStatus(200);



    }
}
