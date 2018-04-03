<template>
    <div>

        <form class="form-inline d-flex flex-row">

            <h6 class="d-md-none d-sm-inline mr-4 text-primary">{{from}} - {{to}}</h6>

            <div>

                <div class="input-group input-daterange float-right">



                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary"
                                v-on:click="prev"
                                dusk="btn-date-range-prev-control">
                            <i class="fa fa-lg fa-arrow-circle-left" aria-hidden="true"></i>
                        </button>
                    </div>

                    <input name="from" id="input-date-from-control" type="text" class="d-none d-md-inline form-control" v-model="from" dusk="input-date-from-control">
                    <div class="input-group-append d-none d-md-flex">
                        <div class="input-group-text">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </div>
                    </div>
                    <input name="to" id="input-date-to-control" type="text" class="d-none d-md-inline form-control" v-model="to" dusk="input-date-to-control">
                    <div class="input-group-append d-none d-md-flex">
                        <div class="input-group-text">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary"
                                v-on:click="next"
                                dusk="btn-date-range-next-control">
                            <i class="fa fa-lg fa-arrow-circle-right" aria-hidden="true"></i>
                        </button>
                    </div>
                    <!--
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" v-on:click="applyDateRange" dusk="btn-apply-control">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </button>
                    </div>
                    -->
                </div>
            </div>






        </form>
    </div>
</template>

<script>
    export default {
        name: "date-range-selector",

        props: ['http'],

        data() {

            return {
                from: '',
                to: ''
            }
        },


        mounted() {

            let $this = this;

            $('.input-daterange')
                .datepicker({format: "yyyy-mm-dd", autoclose: true})
                .on('changeDate', function(e) {

                    console.log('Date from changed!');
                    console.log(e);
                    $this.from = $('#input-date-from-control').val();
                    $this.to = $('#input-date-to-control').val();
                    $this.applyDateRange();

            });






            this.from = moment().startOf('month').format('YYYY-MM-DD');
            this.to = moment().endOf('month').format('YYYY-MM-DD');

            this.applyDateRange();

        },


        methods: {

            applyDateRange: function() {

                $('.input-daterange input[name="from"]').datepicker('update', this.from);
                $('.input-daterange input[name="to"]').datepicker('update', this.to);


                this.$bus.$emit('date-range-applied', {from: this.from, to: this.to});

            },

            diff: function (from, to) {

                let differences = ['years', 'months', 'days'];

                let unit = 'days';
                let diff = 1;

                while (differences.length > 0) {

                    unit = differences.shift();
                    diff = to.diff(from, unit);

                    if ( diff > 0) {
                        break;
                    }

                }

                return {diff: diff, unit: unit}

            },

            next: function () {

                let mFrom = moment(this.from);
                let mTo = moment(this.to).add(1, 'days');

                let {diff, unit} = this.diff(mFrom, mTo);


                this.from = mFrom.add(diff, unit).format('YYYY-MM-DD');
                this.to = mTo.add(diff, unit).subtract(1, 'days').format('YYYY-MM-DD');

                this.applyDateRange();



            },

            prev: function () {

                let mFrom = moment(this.from);
                let mTo  = moment(this.to).add(1, 'days');;

                let {diff, unit} = this.diff(mFrom, mTo);

                this.from = mFrom.subtract(diff, unit).format('YYYY-MM-DD');
                this.to = mTo.subtract(diff, unit).subtract(1, 'days').format('YYYY-MM-DD');

                this.applyDateRange();






            }

        }


    }
</script>

<style scoped>

</style>