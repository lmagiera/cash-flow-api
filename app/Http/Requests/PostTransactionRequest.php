<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'transaction' => 'required',
            'transaction.description' => 'required|max:150',
            'transaction.amount' => ['required', Rule::notIn([0])],
            'transaction.planned_on' => 'required|date_format:"Y-m-d"',
            'transaction.repeating_interval' => ['required', Rule::in([0, 1, 2, 3])]

        ];
    }
}
