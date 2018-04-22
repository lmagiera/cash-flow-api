import MessageBoxDialog from "./dialogs/MessageBoxDialog";
import MessageBoxWrapper from "./dialogs/MessageBoxWrapper";

const MessageBoxState = {
    id: 0
};

const MessageBox = {

    id: 1,

    createInsance(opt) {

        MessageBoxState.id += 1;

        const MessageBoxDialogClass = window.Vue.extend(MessageBoxDialog);

        const instance = new MessageBoxDialogClass({
            propsData: {

                title: opt.title,
                message: opt.message,
                type: opt.type,
                buttons: opt.buttons,
                modalId: MessageBoxState.id

            }
        });

        return instance;



    },

    install(Vue, options) {

        Vue.component(MessageBoxWrapper.name, MessageBoxWrapper);

        Vue.$dialog = {

            MessageBoxType: Object.freeze({
                Ok: "Ok",
                YesNo: "YesNo",
                YesNoCancel: "YesNoCancel",
            }),
            DialogResult: Object.freeze({
                ResultOk: "ResultOK",
                ResultCancel: "ResultCancel"
            }),

            app: null,

            show(opt) {

                const instance = MessageBox.createInsance(opt);

                instance.$mount();

                Vue.$dialog.app.$refs.container.appendChild(instance.$el);

                $("#modal-" + MessageBoxState.id).on("hide.bs.modal", function (e) {

                    const dialogResult = instance.dialogResult == null ? "Cancel" : instance.dialogResult;
                    Vue.$dialog.app.$refs.container.removeChild(instance.$el);
                    opt.onclose.call(this, dialogResult);

                });

                $("#modal-" + MessageBoxState.id).modal("show");

            }
        };

    }

};

export default MessageBox;
