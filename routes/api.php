<?php

use App\Http\Requests\GetTransactionsRequest;
use App\Http\Requests\PostTransactionRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

Route::middleware(['auth:api'])->get('/transaction', function (Request $request) {

    $getRequest = new GetTransactionsRequest();


    $validator = Validator::make($request->query(), $getRequest->rules());

    $dateStart = $request->query('from');
    $dateEnd = $request->query('to');

    if ( !$validator->validate() ) {
        $dateStart = \Carbon\Carbon::now()->startOfMonth()->subMonth(6);
        $dateEnd = \Carbon\Carbon::now()->endOfMonth()->addMonth(6);
    }


    $transactions = Transaction::between($dateStart, $dateEnd)->ordered()->get();

    return new TransactionCollection($transactions);

});

Route::middleware(['auth:api'])->get('/transaction/{id}', function(Request $request, $id) {

    $transaction = Transaction::where(['id' => $id])->firstOrFail();
    return new TransactionResource($transaction);

});

Route::middleware(['auth:api'])->post('/transaction', function(PostTransactionRequest $request) {

    $transactionData =
        $request->json()->get('transaction');

    if ($transactionData['repeating_interval'] != 0) {

        $firstDate = (new Carbon($transactionData['planned_on']))->format('Y-m-d');

        //TODO: this do not scale well..
        //TODO: get better at generating this number/id
        $uiq = uniqid('RPT-', true);

        for ($c = 0; $c < 50; $c++) {

            $transaction = new Transaction($transactionData);
            $transaction->planned_on = $firstDate;
            $transaction->user_id = Auth::id();
            $transaction->repeating_id = $uiq;

            $transaction->save();

            $firstDate = (new Carbon($firstDate))->addMonth($transactionData['repeating_interval'])->format('Y-m-d');

        }


    }
    else {
        $transaction = new Transaction($transactionData);
        $transaction->user_id = Auth::id();
        $transaction->save();
    }




});


