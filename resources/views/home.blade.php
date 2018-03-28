@extends('layouts.app')

@section('content')


<div class="container">

    <div class="row mb-4">
        <div class="col-3">
            <tool-bar dusk="tool-bar-component" v-bind:http="HTTP" v-on:transaction="addTransaction"></tool-bar>
        </div>
        <div class="col-9 d-flex justify-content-end">
            <date-range-selector dusk="date-range-selector-component"></date-range-selector>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <transaction-list v-bind:HTTP="HTTP"></transaction-list>

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            Some nice text here
        </div>
    </div>

</div>

@endsection
