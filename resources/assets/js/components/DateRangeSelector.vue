<template>
    <div>
        <form class="form-inline">

            <label class="mr-2">Select Date Range</label>

            <div class="input-group input-daterange">
                <input name="from" id="input-date-from-control" type="text" class="form-control" v-model="from" dusk="input-date-from-control">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
                <input name="to" id="input-date-to-control" type="text" class="form-control" v-model="to" dusk="input-date-to-control">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>



            <button type="button" class="btn btn-primary ml-2" v-on:click="applyDateRange" dusk="btn-apply-control">Apply</button>

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

            var $this = this;

            $('.input-daterange')
                .datepicker({format: "yyyy-mm-dd", autoclose: true})
                .on('changeDate', function(e) {
                console.log('Date from changed!');
                console.log(e);
                $this.from = $('#input-date-from-control').val();
                $this.to = $('#input-date-to-control').val();
            });






            this.from = moment().startOf('month').format('YYYY-MM-DD');
            this.to = moment().endOf('month').format('YYYY-MM-DD');

            this.applyDateRange();

        },


        methods: {

            applyDateRange: function() {

                this.$bus.$emit('date-range-applied', {from: this.from, to: this.to});

            }
        }


    }
</script>

<style scoped>

</style>