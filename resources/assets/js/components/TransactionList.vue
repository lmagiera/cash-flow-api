<template>

    <div>



        <ul class="nav nav-tabs" role="tablist" dusk="tab-nav-transaction-list">
            <li class="nav-item">
                <a class="nav-link active" id="tab-transaction-list-tab" data-toggle="tab" href="#tab-transaction-list">Transactions</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="tab-graphs-tab" data-toggle="tab" href="#tab-graphs">Graphs</a>
            </li>

        </ul>

        <div class="tab-content mt-1" id="transaction-list-tab-content">
            <div class="tab-pane show fade active" id="tab-transaction-list" role="tabpanel">

                <h5 class="display-5 p-2">Transaction List</h5>

                <table class="table table-sm table-striped" dusk="table-transaction-list">
                    <thead class="thead-dark font-weight-bold">
                        <tr>
                            <td scope="col" class="d-none d-md-inline">#</td>
                            <td scope="col" class="d-none">Id</td>
                            <td scope="col">Description</td>
                            <td scope="col">Planned On</td>
                            <td scope="col">&nbsp;</td>
                            <td scope="col" class="text-right">Amount</td>
                            <td scope="col">&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr scope="row" v-for="(item, index) in transactions.data">
                        <td class="d-none d-md-inline">{{index + 1}}</td>
                        <td class="d-none">{{item.id}}</td>
                        <td>{{item.description}}</td>
                        <td>{{item.planned_on}}</td>
                        <td><i v-if="item.repeating_interval > 0" class="fa fa-lg fa-repeat" aria-hidden="true"></i></td>
                        <td class="text-right">{{item.amount}}</td>
                        <td class="text-right">
                            <div class="d-inline-flex ml-2">
                                <button class="btn btn-sm btn-outline-success mr-1" v-on:click="edit(item)" dusk="btn-remove-transaction-control">
                                    <i class="fa  fa-pencil" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" v-on:click="remove(item)" dusk="btn-remove-transaction-control">
                                    <i class="fa  fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="tab-pane fade" id="tab-graphs">
                Graphs will be here
            </div>

        </div>

    </div>

</template>

<script>
    export default {

        name: "transaction-list",
        props: ['http'],

        data() {

            return {
                transactions: [],
                from: '',
                to: ''
            }

        },

        methods: {

            refresh : function () {

                let url = 'transaction';

                if (this.from != '' && this.to != '') {
                    url += '?from=' + this.from + '&to=' + this.to;
                }

                console.log('Refersh ' + url);

                this.http.get(url).then(response => {

                    this.transactions = response.data;
                    console.log(response.data);

                }).catch(e => {
                    console.error(e);
                })

            },

            edit: function(item) {

                const transaction = jQuery.extend({}, item); // pass the copy.
                this.$bus.$emit('transaction-edit', {transaction: transaction});

            },

            remove: function(item) {

                if ( !confirm('Are you sure you want to remove transaction?') ) {
                    return;
                }

                this.http.delete('transaction/' + item.id).then(response => {


                    this.refresh();


                }).catch(e => {
                    console.error(e);
                })

            }

        },

        created() {

            //this.refresh();




            console.log('Subscribing to transactions!');

            this.$bus.$on('new-transaction', event => {

                this.refresh();

            });

            console.log('Subscribing to date-range-applied!');

            this.$bus.$on('date-range-applied', event => {

                this.from = event.from;
                this.to = event.to;

                this.refresh();

                console.log('Refreshed on date range applied');

            });


        }

    }
</script>

<style scoped>

</style>