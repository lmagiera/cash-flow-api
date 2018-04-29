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
                },

                pristineErrors: {
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
                toolbar.hasErrors = false;
                toolbar.errors = jQuery.extend({}, this.pristineErrors);


                console.log('Modal hidden: ' + toolbar.editing);
            });



        },

        created() {

            this.$bus.$on('transaction-edit', (event) => {

                this.edit(event.transaction)

            });
        },



        methods: {

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

            uploadTransaction() {

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

                    //this.$emit('transaction', {transaction: this.transaction});

                    toolbar.editing = false;
                    toolbar.transaction = jQuery.extend({}, toolbar.pristineTransaction);
                    toolbar.editingTransaction = jQuery.extend({}, toolbar.pristineTransaction);
                    $('#modal-add-transaction').modal('hide');
                    this.$notifier.success("Transaction saved!");


                }).catch(e => {

                    console.log(e.response.status);

                    switch (e.response.status.toString()) {
                        case '422':
                            this.hasErrors = true;
                            this.errors = e.response.data.errors;
                            break;
                        default:
                            this.$notifier.danger("There was an error processing your request.<br>" + e.response.status + ": " + e.response.statusText);

                    }


                });



            },


            /**
             * Sends transation event up
             */
            sendTransaction: function () {


                this.transaction['update_all'] = false;

                if (!this.editing) {
                    this.uploadTransaction();
                    return;
                }

                //  This is a case, when we changed repeating interval no 'None',
                //  we should update all transactions, removing any repeating one
                if (this.transaction.repeating_interval == 0) {
                    this.transaction['update_all'] = true;
                    this.uploadTransaction();
                    return;

                }

                const isChanginInterval = this.editingTransaction.repeating_interval != this.transaction.repeating_interval;
                const isChangingDate = this.editingTransaction.planned_on != this.transaction.planned_on;
                const isChangingDateAndInteval = isChanginInterval && isChangingDate;

                let title = "Edit Transaction" + this.editingTransaction.description;

                let message = "Update all occurrences? Click Yes to update all or No to update only this occurrence";
                let buttons = {Yes: {text: "Yes, update all"}, No: {text: "No, update single one"}, Cancel: {text: "Cancel"}};
                let type = "YesNoCancel";

                const $this = this;

                // this is the case, when no interval or date is changed,
                // give option to update all or one
                if ((!isChangingDate && !isChanginInterval) || (isChangingDate && !isChangingDateAndInteval)) {

                    window.Vue.$dialog.show({title, message, type, buttons, onclose: function(result){

                            if ( result === "Cancel") {
                                return;
                            }

                            if ( result === "Yes") {
                                $this.transaction['update_all'] = true;
                            }

                            $this.uploadTransaction();

                        }});

                    return;

                }

                if ( isChangingDateAndInteval || isChanginInterval ) {

                    // we are dealing with date or date and interval changes,
                    // all future instances wiil be recalculated.

                    type = "YesNo";
                    buttons = {Yes: {text: "Yes, update all"}, No: {text: "No, Cancel"}, Cancel: {text: "Cancel"}};
                    message = "This operation will affect all of the occurrences of the transaction"

                    window.Vue.$dialog.show({title, message, type, buttons, onclose: function(result){

                            if (result === "Cancel") {
                                return;
                            }
                            if ( result === "Yes") {
                                $this.transaction['update_all'] = true;
                            }
                            $this.uploadTransaction();

                        }});

                    // return; // Not needed but nevertheless.
                }
            }
        },

        components: {
            'repeat-selector': require('./RepeatSelector.vue')
        }
    }
</script>

<style scoped>

</style>