<template>

    <div>

        <div class="modal fade message-box" tabindex="-1" role="dialog" :id="'modal-' + modalId">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{message}}</p>
                    </div>
                    <div class="modal-footer flex-column-reverse flex-md-row">

                        <template v-if="type === 'YesNoCancel'">
                            <button id="btn-cancel-control" v-on:click="result('Cancel')" type="button" class="btn btn-block-xs-only btn-secondary mr-md-auto m-0" data-dismiss="modal">{{buttons.Cancel.text}}</button>
                            <button id="btn-no-control" type="button" v-on:click="result('No')" class="btn btn-block-xs-only btn-outline-primary m-1" data-dismiss="modal">{{buttons.No.text}}</button>
                            <button id="btn-yes-control" dusk="dialog-btn-yes-control" type="button" v-on:click="result('Yes')" class="btn btn-block-xs-only btn-primary m-0" data-dismiss="modal">{{buttons.Yes.text}}</button>
                        </template>

                        <template v-if="type === 'YesNo'">
                            <button id="btn-no-control"  type="button" v-on:click="result('No')" class="btn btn-block-xs-only btn-outline-primary m-1" data-dismiss="modal">{{buttons.No.text}}</button>
                            <button id="btn-yes-control" dusk="dialog-btn-yes-control" type="button" v-on:click="result('Yes')" class="btn btn-block-xs-only btn-primary m-0" data-dismiss="modal">{{buttons.Yes.text}}</button>
                        </template>

                        <template v-if="type === 'Ok'">
                            <button id="btn-yes-control" dusk="dialog-btn-yes-control" type="button" v-on:click="result('Yes')" class="btn btn-block-xs-only btn-primary m-0" data-dismiss="modal">{{buttons.Yes.text}}</button>
                        </template>


                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: ['title', 'message', 'type', 'modalId', 'buttons'],

        methods: {
            result(result) {
                this.dialogResult = result;
                const mid = "#modal-" + this.modalId;
                console.error("Hiding modal: " + mid + " " + this.dialogResult );
                $(mid).modal('hide');
            }
        },

        data() {
            return {
                dialogResult: "Some"
            }
       },

       name: "MessageBoxDialog"
    }
</script>

<style scoped>

</style>