<?php

namespace App\Http\Resources;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CashFlowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        if ( !$from || !$to) {

            $from = Carbon::now()->startOfMonth()->subDay()->format('Y-m-d');
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');

        }

        $startFrom = Carbon::now()->startOfMonth()->format('Y-m-d');

        $beforeSum = DB::table('transactions')
            ->where('user_id', Auth::id())
            ->where('planned_on', '<=', $from)
            ->sum('amount');

        $runningTotal = $beforeSum;
        $cashFlowData = [];


        DB::enableQueryLog();

        /** @var $cashFlowItems Collection */
        $cashFlowItems = Transaction::between($startFrom, $to)
            ->select('planned_on')
            ->selectRaw('sum(amount) as sum_amount')
            ->groupBy('planned_on')
            ->get();


        $cashFlowItems->each(function ($row) use (&$runningTotal, &$cashFlowData) {

            $cashFlowData[] = [
                'date' => $row->planned_on,
                'amount' => currency($row->sum_amount),
                'saldo' => currency($runningTotal + $row->sum_amount)
            ];
            $runningTotal = $runningTotal + $row->sum_amount;

        });



        return [
            'cash_flow_start' => [
                'date' => $from,
                'amount' => $beforeSum
            ],
            'cash_flow_end' => [
                'date' => $to,
                'amount' => currency($runningTotal)
            ],
            'cash_flow_data' => $cashFlowData
        ];
    }
}
