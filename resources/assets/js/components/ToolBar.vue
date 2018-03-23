<template>
<span class="align-items-center">

<a class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" href="#" dusk="btn-add-transaction">Add New Transaction</a>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
    <form>


        <div class="form-group" dusk="input-description">
            <label for="transaction-description" class="col-form-label">Transaction Description:</label>
            <input type="text" class="form-control" id="transaction-description" v-model="transaction.description">
        </div>

        <div class="form-group" dusk="input-amount">
            <label for="transaction-amount" class="col-form-label">Amount:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="transaction-amount" v-model="transaction.amount">
                <div class="input-group-append">
                    <div class="input-group-text">PLN</div>
                </div>
            </div>
        </div>

        <div class="form-group" dusk="input-planned-on">
            <label for="transaction-planned-on" class="col-form-label">Date Planned At:</label>
            <input type="text" class="form-control" id="transaction-planned-on" v-model="transaction.planned_on">
        </div>

        <div class="form-group" dusk="input-actual-on">
            <label for="transaction-actual-on" class="col-form-label">Date Actual At:</label>
            <input type="text" class="form-control" id="transaction-actual-on" v-model="transaction.actual_on">
        </div>

        <div class="form-group" dusk="input-varying">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="transaction-varying">
                <label class="custom-control-label" for="transaction-varying">Varying</label>
            </div>
        </div>

    </form>
    </div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" dusk="btn-close-add-new-transaction" >Cancel</button>
    <button type="button" class="btn btn-success" dusk="btn-save-transaction">Save</button>
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
                    actual_on: ''
                }
            }
        },

        methods: {

            showModal: function () {

            },


            /**
             * Sends transation event up
             */
            sendTransaction: function () {
                console.log('Emmiting Transation');
                this.$emit('transaction', {transaction: 'test'});
            }
        }
    }
</script>

<style scoped>

</style>