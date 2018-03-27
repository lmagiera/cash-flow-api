<template>
    <div>
        <form class="form-inline">

            <label class="mr-3">Select Date Range</label>
            <input name="from" type="text" class="form-control mr-2" v-model="from" dusk="input-date-from-control">
            <label class="mr-2"> - </label>
            <input name="to" type="text" class="form-control mr-2" v-model="to" dusk="input-date-to-control">

            <button type="button" class="btn btn-primary" v-on:click="applyDateRange" dusk="btn-apply-control">Apply</button>

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


        methods: {

            applyDateRange: function() {

                this.$bus.$emit('date-range-applied', {from: this.from, to: this.to});

            }
        }


    }
</script>

<style scoped>

</style>