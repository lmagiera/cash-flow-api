<template>
<span class="align-items-center">

<a class="btn btn-primary" data-toggle="modal" data-target="#modal-add-transaction" href="#" dusk="btn-add-transaction">Add New Transaction</a>

<div class="modal fade" id="modal-add-transaction"
     tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true" dusk="modal-add-transaction">

<div class="modal-dialog" role="document">
<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Transaction</h5>
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
            <input type="text" class="form-control" id="transaction-planned-on"
                   v-bind:class="validation.transaction.planned_on" v-model="transaction.planned_on">
            <div class="invalid-feedback" dusk="feedback-invalid-planned-on">{{ errors["transaction.planned_on"] }}</div>
        </div>

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

        <div class="form-group" dusk="input-repeating">
            <repeat-selector v-bind:hasErrors="hasErrors"
                             v-bind:errors="errors"
                             v-bind:validation="validation"
                             v-bind:transaction="transaction"
                             v-on:select="selectRepeating" dusk="repeat-interval-component"></repeat-selector>
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

                hasErrors: false,

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
                            'is-invalid': this.errors['transaction.description']
                        },
                        amount: {
                            'is-invalid': this.errors['transaction.amount']
                        },
                        planned_on: {
                            'is-invalid': this.errors['transaction.planned_on']
                        },
                        /*
                        actual_on: {
                            'is-invalid': this.errors['transaction.actual_on']
                        },
                        */
                        repeating_interval: {
                            'is-invalid': this.errors['transaction.repeating_interval']
                        }
                    }
                }
            }
        },

        props: ['http'],

        methods: {

            showModal: function () {

                // should clear transaction object here
                console.log("Clear transaction Object here");

            },


            selectRepeating: function (data) {
                console.log('Receive repeating info: ' + data.repeating_interval);
                this.transaction.repeating_interval = data.repeating_interval;
            },


            /**
             * Sends transation event up
             */
            sendTransaction: function () {


                console.log('Sending transaction on amount: ' + this.transaction.amount + " PLN");

                this.http.post('transaction', {transaction: this.transaction})

                    .then(response => {

                        console.log(response.status);

                        this.$emit('transaction', {transaction: this.transaction});
                        $('#exampleModal').modal('hide');
                    })
                    .catch(e => {

                        this.hasErrors = true;

                        this.errors = e.response.data.errors;


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