<template>
    <div>

        <form class="form-inline d-flex flex-row">

            <div>

                <div class="input-group float-right">

                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary mr-1"
                                v-on:click="prev"
                                dusk="btn-date-range-prev-control">
                            <i class="fa fa-lg fa-arrow-circle-left" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="datepicker-trigger">
                        <button
                                class="btn btn-outline-primary mr-1"
                                type="text"
                                id="datepicker-trigger"
                                placeholder="Select dates"
                                :value="formatDates(from, to)"
                                dusk="btn-select-date-range-control"
                        >{{from}} | {{to}}</button>

                        <AirbnbStyleDatepicker

                                :trigger-element-id="'datepicker-trigger'"
                                :mode="'range'"
                                :fullscreen-mobile="true"
                                :date-one="from"
                                :date-two="to"
                                @date-one-selected="val => { from = val }"
                                @date-two-selected="val => { to = val }"
                                @apply="applyDateRange"
                        />

                    </div>

                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary"
                                v-on:click="next"
                                dusk="btn-date-range-next-control">
                            <i class="fa fa-lg fa-arrow-circle-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>


    </div>
</template>

<script>

    import format from 'date-fns/format';

    export default {

        name: "date-range-selector",

        props: ['http'],

        data() {

            return {
                from: '',
                to: '',
                dateFormat: 'YYYY-MM-DD',
            }
        },


        mounted() {

            this.from = moment().startOf('month').format('YYYY-MM-DD');
            this.to = moment().endOf('month').format('YYYY-MM-DD');

            this.applyDateRange();

        },


        methods: {

            formatDates(dateOne, dateTwo) {
                let formattedDates = ''
                if (dateOne) {
                    formattedDates = format(dateOne, this.dateFormat)
                }
                if (dateTwo) {
                    formattedDates += ' | ' + format(dateTwo, this.dateFormat)
                }
                return formattedDates
            },

            applyDateRange: function() {

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