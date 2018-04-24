<template>

    <div>



        <ul class="nav nav-tabs" role="tablist" dusk="tab-nav-transaction-list">
            <li class="nav-item">
                <a class="nav-link active" id="tab-transaction-list-tab" data-toggle="tab" href="#tab-transaction-list">Transactions</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="tab-graphs-tab" data-toggle="tab" href="#tab-graphs" dusk="tab-cash-flow-control">Cash Flow</a>
            </li>

        </ul>

        <div class="tab-content mt-1" id="transaction-list-tab-content">
            <div class="tab-pane show fade active" id="tab-transaction-list" role="tabpanel">

                <h5 class="d-none d-md-block display-5 p-2">Transaction List</h5>







                <table class="table table-sm table-striped" dusk="table-transaction-list">
                    <thead class="thead-dark font-weight-bold">
                        <tr>
                            <td scope="col" class="d-none d-md-block">#</td>
                            <td scope="col">Description</td>
                            <td scope="col">Planned On</td>
                            <td scope="col">&nbsp;</td>
                            <td scope="col" class="text-right">Amount</td>
                            <td scope="col">&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr scope="row" v-for="(item, index) in transactions.data">
                        <td class="d-none d-md-block">{{index + 1}}</td>
                        <td>{{item.description}}</td>
                        <td>{{item.planned_on}}</td>
                        <td><i v-if="item.repeating_interval > 0" class="fa text-muted fa-lg fa-repeat" aria-hidden="true"></i></td>
                        <td class="text-right">{{item.amount}}</td>
                        <td class="text-right">
                            <div class="d-inline-flex ml-2">
                                <button class="btn btn-sm btn-outline-success mr-1" v-on:click="edit(item)" dusk="btn-edit-transaction-control">
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

            <div class="tab-pane fade" id="tab-graphs" role="tabpanel">
                <div id="chart-canvas-container" style="position: relative; width: 99%" dusk="graph-cash-flow">
                    <canvas id="chart-canvas"></canvas>
                </div>
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
                to: '',
                cashflowData: {
                    data: []
                },
                chart: {
                    instance: null
                }



            }

        },

        components: {

            //"message-box-dialog": require("./dialogs/MessageBoxDialog.vue")

        },



        methods: {

            refresh : function () {

                let url = 'transaction';

                if (this.from !== '' && this.to !== '') {
                    url += '?from=' + this.from + '&to=' + this.to;
                }

                this.http.get(url).then(response => {

                    this.transactions = response.data;

                }).catch(e => {
                    this.$notifier.danger("There was an error processing your request.<br>" + e.response.status + ": " + e.response.statusText);
                })

            },

            cashflow: function () {

                let url = "cashflow";

                if (this.from !== '' && this.to !== '') {
                    url += '?from=' + this.from + '&to=' + this.to;
                }

                this.http.get(url).then(response => {

                    const mydata = response.data.data;

                    let labels = [];
                    let data = [];

                    labels.push(mydata.cash_flow_start.date);
                    data.push(mydata.cash_flow_start.amount);

                    $(mydata.cash_flow_data).each(function(key, item){

                        labels.push(item.date);
                        data.push(item.saldo);

                    });


                    labels.push(mydata.cash_flow_end.date);
                    data.push(mydata.cash_flow_end.amount);

                    this.chart.instance.data.labels = labels;
                    this.chart.instance.data.datasets[0].data = data;
                    this.chart.instance.update();


                }).catch(e => {
                    console.log(e);
                    this.$notifier.danger("There was an error processing your request.<br>" + e.response.status + ": " + e.response.statusText);
                })


            },

            edit: function(item) {

                const transaction = jQuery.extend({}, item); // pass the copy.
                this.$bus.$emit('transaction-edit', {transaction: transaction});

            },

            remove: function(item) {


                let message = "Are you sure you want to remove this transaction? This cannot be undone!";
                let type = "YesNo";

                let buttons = {Yes: {text: "Yes, remove transaction"}, No: {text: "No"}, Cancel: {text: "Cancel"}};

                if (item.repeating_interval > 0) {
                    message = "Would you like to remove all transactions?";
                    type = "YesNoCancel";
                    buttons.Yes.text = "Yes, remove all";
                    buttons.No.text = "No, remove single one";

                }

                const title = "Remove transaction: " + item.description;

                const $this = this;

                Vue.$dialog.show({title, message, type, buttons,

                    onclose: function (result) {

                        console.error('Modal is hiding: result: ' + result);

                        if ( result === "Cancel") {
                            return;
                        }

                        if ( result === "No" && item.repeating_interval === 0) {
                            return;
                        }

                        let url = "transaction/" + item.id;

                        if ( result === "Yes" && item.repeating_interval > 0) {
                            url += "?all";
                        }

                        $this.http.delete(url).then(response => {


                            $this.refresh();
                            $this.cashflow();


                        }).catch(e => {
                            $this.$notifier.danger("There was an error processing your request.<br>" + e.response.status + ": " + e.response.statusText);
                        })

                }})
            }
        },

        mounted() {

            const $list = this;


            let ctx = $("#chart-canvas");

            this.chart.instance = new chart(ctx, {
                type: 'line',
                data: {
                    labels: this.chart.labels,
                    datasets: [{
                        label: 'Cash Flow',
                        data: this.chart.data,
                        backgroundColor: ['rgba(144, 248, 53, 0.42)']
                    }]

                },
                options: {scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }}
            });



            // hook tab changing and refresh cashflow view

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                e.target // newly activated tab
                e.relatedTarget // previous active tab


                if ( $(e.target).text().toLowerCase() != "cash flow") {
                    return;
                }

                $list.cashflow()

            });

        },

        created() {


            console.log('Subscribing to transactions!');

            this.$bus.$on('new-transaction', event => {

                this.refresh();
                this.cashflow();

            });

            console.log('Subscribing to date-range-applied!');

            this.$bus.$on('date-range-applied', event => {

                this.from = event.from;
                this.to = event.to;

                this.refresh();
                this.cashflow();

                console.log('Refreshed on date range applied');

            });


            console.log("canvas",  document.getElementById("#chart-canvas"));
            return;






        }

    }
</script>

<style scoped>

</style>