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
                            <td scope="col">#</td>
                            <td scope="col" style="width: 75%">Description</td>
                            <td scope="col">Planned On</td>
                            <td scope="col">Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr scope="row" v-for="(item, index) in transactions.data">
                        <td >{{index+1}}</td>
                        <td>{{item.description}}</td>
                        <td>{{item.planned_on}}</td>
                        <td>{{item.amount}}</td>
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