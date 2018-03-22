@extends('layouts.app')

@section('content')


<div class="container-fluid flex">
    <div class="row">
        <div class="col-6">
            <user-saldo></user-saldo>
        </div>
        <div class="col-6">
            <date-selector></date-selector>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <cash-flow-graph></cash-flow-graph>
        </div>
    </div>
</div>

@endsection
