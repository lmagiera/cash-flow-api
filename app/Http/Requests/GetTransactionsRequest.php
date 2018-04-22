<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class GetTransactionsRequest extends Request
{

    protected $method = 'GET';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'date_format:"Y-m-d"|before_or_equal:to',
            'to' => 'date_format:"Y-m-d"|after_or_equal:from'
        ];
    }
}
