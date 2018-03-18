<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/transaction', function (Request $request) {

    $user = \Illuminate\Support\Facades\Auth::user();
    $transactions = \App\Transaction::find(['user_id' => $user->id]);

    return new \App\Http\Resources\TransactionCollection($transactions);

});
