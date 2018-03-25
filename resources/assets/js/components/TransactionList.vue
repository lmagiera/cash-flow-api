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

        <div class="tab-content" id="transaction-list-tab-content">
            <div class="tab-pane show fade active" id="tab-transaction-list" role="tabpanel">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr scope="row">
                            <td>#</td>
                            <td>Description</td>
                            <td>Planned On</td>
                            <td>Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr scope="row" v-for="(item, index) in transactions.data">
                        <td scope="col">{{index}}</td>
                        <td scope="col">{{item.description}}</td>
                        <td scope="col">{{item.planned_on}}</td>
                        <td scope="col">{{item.amount}}</td>
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
                transactions: []
            }

        },

        methods: {

            refresh : function () {

                this.http.get('transaction').then(response => {

                    this.transactions = response.data;
                    console.log(response.data);

                }).catch(e => {
                    console.error(e);
                })

            }

        },

        created() {

            this.refresh();

        },

        mounted() {
            console.log('Subscribing to transactions!');

            this.$bus.$on('new-transaction', event => {

                this.refresh();

            });



        }

    }
</script>

<style scoped>

</style>