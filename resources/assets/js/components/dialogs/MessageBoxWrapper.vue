<template>
    <div ref="container">This is it</div>
</template>

<script>
    export default {
        name: "message-box-wrapper",
        methods: {
            add() {
                console.log("Add Called on wrapper!");
            }
        }
    }
</script>

<style scoped>

</style>