<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
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

        return [
            'cash_flow_start' => [
                'date' => $from,
                'amount' => $beforeSum
            ],
            'cash_flow_end' => [
                'date' => $to,
                'amount' => 0
            ],
            'cash_flow_data' => [
                ['day' => $startFrom, 'amount' => 0]
            ]
        ];
    }
}
