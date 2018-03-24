<template>

    <div id="repeat-selector">
        <label for="transaction-repeat" class="col-form-label">Repeat:</label>
        <select class="custom-select" id="transaction-repeat" v-model="repeating_interval" dusk="input-repeating-control">
            <option selected value="0">None</option>
            <option value="1">Monthly</option>
            <option value="2">Every 2 Months</option>
            <option value="3">Every 3 Months</option>
        </select>
        {{transaction}} {{repeating_interval}}
    </div>

</template>

<script>
    export default {
        name: "repeat-selector",

        props: ['transaction'],

        data: function() {
            return {repeating_interval: this.transaction.repeating_interval}
        },

        watch: {repeating_interval: function () {
                console.log('Repeating interval Changed');
                this.$emit('select', {repeating_interval: this.repeating_interval});
        }},

    }
</script>

<style scoped>

</style>