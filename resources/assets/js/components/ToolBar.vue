<template>
<span class="align-items-center">

<a class="btn btn-primary" data-toggle="modal" data-target="#modal-add-transaction" href="#" dusk="btn-add-transaction">
    <span class="d-none d-md-inline">Add New Transaction</span>
    <i class="fa fa-plus-square-o" aria-hidden="true"></i>
</a>

<div class="modal fade" id="modal-add-transaction"
     tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true" dusk="modal-add-transaction">

<div class="modal-dialog" role="document">
<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" v-bind:class="editing ? 'd-none': ''">
            Add New Transaction
        </h5>
        <h5 class="modal-title" id="exampleModalLabel" v-bind:class="editing ? '': 'd-none'">
            <strong>{{transaction.description}}</strong>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">

        <div class="alert alert-danger alert-dismissible fade show" v-if="hasErrors" role="alert" dusk="alert-invalid-transaction">
            There were errors processing your request
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    <form>


        <div class="form-group" dusk="input-description">
            <label for="transaction-description" class="col-form-label">Transaction Description:</label>
            <input type="text" class="form-control" id="transaction-description"
                   v-bind:class="validation.transaction.description" v-model="transaction.description">
            <div class="invalid-feedback" dusk="feedback-invalid-description">{{ errors["transaction.description"] }}</div>
        </div>

        <div class="form-group" dusk="input-amount">
            <label for="transaction-amount" class="col-form-label">Amount:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="transaction-amount"
                       v-bind:class="validation.transaction.amount" v-model="transaction.amount">
                <div class="input-group-append">
                    <div class="input-group-text">PLN</div>
                </div>
                <div class="invalid-feedback" dusk="feedback-invalid-amount">{{ errors["transaction.amount"] }}</div>
            </div>
        </div>

        <div class="form-group" dusk="input-planned-on">
            <label for="transaction-planned-on" class="col-form-label">Date Planned At:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="transaction-planned-on" v-bind:class="validation.transaction.planned_on" v-model="transaction.planned_on">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="invalid-feedback" dusk="feedback-invalid-planned-on">{{ errors["transaction.planned_on"] }}</div>
            </div>

        </div>

        <!--
        <div class="form-group" dusk="input-actual-on">
            <label for="transaction-actual-on" class="col-form-label">Date Actual At:</label>
            <input type="text" class="form-control" id="transaction-actual-on"
                    v-model="transaction.actual_on">
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group" dusk="input-varying">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="transaction-varying">
                <label class="custom-control-label" for="transaction-varying">Varying</label>
            </div>
        </div>
        -->

        <div class="form-group" dusk="input-repeating">
            <repeat-selector v-bind:hasErrors="hasErrors"
                             v-bind:errors="errors"
                             v-bind:validation="validation"
                             v-bind:transaction="transaction"
                             v-on:select="selectRepeating" dusk="repeat-interval-component">test</repeat-selector>
        </div>


    </form>
    </div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"
            dusk="btn-close-add-new-transaction">Cancel</button>
    <button type="button" class="btn btn-success" v-on:click="sendTransaction" dusk="btn-save-transaction">Save</button>
</div>
</div>
</div>
</div>

</span>
</template>

<script>



    export default {

        name: "tool-bar",

        data: function () {

            return {

                transaction: {

                    amount: 0,
                    description: '',
                    planned_on: '',
                    actual_on: '',
                    repeating_interval: 0
                },

                editingTransaction: {
                    amount: 0,
                    description: '',
                    planned_on: '',
                    actual_on: '',
                    repeating_interval: 0
                },

                pristineTransaction: {
                    amount: 0,
                    description: '',
                    planned_on: '',
                    actual_on: '',
                    repeating_interval: 0
                },

                hasErrors: false,
                editing: false,

                errors: {
                    'transaction.description': false,
                    'transaction.amount': false,
                    'transaction.planned_on': false,
                    //'transaction.actual_on': false,
                    'transaction.repeating_interval': false
                }

            }
        },

        computed: {
            validation: function() {
                return {
                    transaction: {
                        description: {
                            'is-invalid': this.errors['transaction.description'] ? true : false
                        },
                        amount: {
                            'is-invalid': this.errors['transaction.amount'] ? true : false
                        },
                        planned_on: {
                            'is-invalid': this.errors['transaction.planned_on'] ? true : false
                        },
                        /*
                        actual_on: {
                            'is-invalid': this.errors['transaction.actual_on']
                        },
                        */
                        repeating_interval: {
                            'is-invalid': this.errors['transaction.repeating_interval'] ? true : false
                        }
                    }
                }
            }
        },

        props: ['http'],

        mounted() {

            console.log('Tool bar mounted');

            const toolbar = this;


            $("#transaction-planned-on").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            }).on('show.bs.modal', function(event) {
                // prevent datepicker from firing bootstrap modal "show.bs.modal"
                event.preventDefault();
                event.stopPropagation();
            }).on('changeDate', function() {
                toolbar.transaction.planned_on = $('#transaction-planned-on').val();
            });

            $('#modal-add-transaction').on('show.bs.modal', function () {

                console.log('Modal about to be shown: ' + toolbar.editing);

                if ( !toolbar.editing) {
                    // clear transaction
                    toolbar.transaction = jQuery.extend({}, toolbar.pristineTransaction);
                }

            });

            $('#modal-add-transaction').on('hidden.bs.modal', function (e) {


                toolbar.transaction = jQuery.extend({}, toolbar.editingTransaction);
                toolbar.editingTransaction = jQuery.extend({}, toolbar.pristineTransaction);
                toolbar.editing = false;

                console.log('Modal hidden: ' + toolbar.editing);
            });



        },

        created() {

            this.$bus.$on('transaction-edit', (event) => {

                this.edit(event.transaction)

            });
        },



        methods: {

            showModal: function () {

                // should clear transaction object here
                console.log("Clear transaction Object here");

            },

            edit(transaction) {

                this.transaction = transaction;
                this.editingTransaction = jQuery.extend({}, this.transaction);
                this.editing = true;



                $('#modal-add-transaction').modal('show');

            },


            selectRepeating: function (data) {
                console.log('Receive repeating info: ' + data.repeating_interval);
                this.transaction.repeating_interval = data.repeating_interval;
            },


            /**
             * Sends transation event up
             */
            sendTransaction: function () {


                console.log('Sending transaction on amount');
                console.log(this.transaction);

                this.transaction['update_all'] = false;

                const isChanginInterval = this.editingTransaction.repeating_interval != this.transaction.repeating_interval;
                const isChangingDate = this.editingTransaction.planned_on != this.transaction.planned_on;
                const isChangingDateAndInteval = isChanginInterval && isChangingDate;

                if (this.editingTransaction.repeating_interval == 0) {

                    // we are changing interval from 0 to non zero,
                    // new transaction will be created any ways
                    this.transaction['update_all'] = true;

                } else {

                    // we are dealing with interval already

                    if ( !isChanginInterval && !isChangingDate ) {
                        // this is a simple case of update
                        if ( confirm('Update all occurences?\n\nClick "OK" to update all or\nClick "Cancel" to update only this instance') ) {
                            this.transaction['update_all'] = true;
                        }
                    }

                    if ( isChangingDateAndInteval || isChanginInterval ) {

                        // we are dealing with date or date and interval changes,
                        // all future instances wiil be recalculated.

                        if ( confirm('This operation will affect all of the occurences of the transaction\nClick "OK" to continue') ) {
                            this.transaction['update_all'] = true;
                        } else {
                            return;
                        }
                   }

                    if (isChangingDate && !isChangingDateAndInteval) {

                        // just changing date, leaving same interval

                        if ( confirm('Update all occurences?\n\nClick "OK" to update all or\nClick "Cancel" to update only this instance') ) {
                            this.transaction['update_all'] = true;
                        }
                    }

                }


                /**/

                let method = 'POST';
                let url = 'transaction';

                if (this.editing) {

                    method = 'PUT';
                    url = url + "/" + this.transaction.id

                }


                this.http({
                    method: method,
                    url: url,
                    data: {transaction: this.transaction}
                }).then(response => {

                        console.log(response.status);

                        this.$bus.$emit('new-transaction', {transaction: this.transaction});

                        this.$emit('transaction', {transaction: this.transaction});

                        toolbar.editing = false;
                        toolbar.transaction = jQuery.extend({}, toolbar.pristineTransaction);
                        toolbar.editingTransaction = jQuery.extend({}, toolbar.pristineTransaction);
                        $('#modal-add-transaction').modal('hide');


                    })
                    .catch(e => {

                        this.hasErrors = true;

                        this.errors = e.response.data.errors;

                        console.log(e.response.data);



                    });
            }
        },

        components: {
            'repeat-selector': require('./RepeatSelector.vue')
        }
    }
</script>

<style scoped>

</style>