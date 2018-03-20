<?php

use App\Http\Requests\PostTransactionRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});

Route::middleware([])->get('/transaction', function (Request $request) {

    $transactions = Transaction::where('user_id', 1)->get();

    return new TransactionCollection($transactions);

});

Route::middleware([])->get('/transaction/{id}', function(Request $request, $id) {

    $transaction = Transaction::where(['user_id' => 1, 'id' => $id])->firstOrFail();
    return new TransactionResource($transaction);

});

Route::middleware([])->post('/transaction', function(PostTransactionRequest $request) {

    $transaction = new Transaction($request->json()->get('transaction'));
    $transaction->save();

});


