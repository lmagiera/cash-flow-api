import MessageBoxDialog from "./dialogs/MessageBoxDialog";
import MessageBoxWrapper from "./dialogs/MessageBoxWrapper";

const MessageBoxState = {
    id: 0
};

const MessageBox = {

    id: 1,

    createInstance(opt) {

        MessageBoxState.id += 1;

        const MessageBoxDialogClass = window.Vue.extend(MessageBoxDialog);

        return new MessageBoxDialogClass({
            propsData: {

                title: opt.title,
                message: opt.message,
                type: opt.type,
                buttons: opt.buttons,
                modalId: MessageBoxState.id

            }
        });

    },

    getSelector() {

        return "#modal-" + MessageBoxState.id;

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

                const instance = MessageBox.createInstance(opt);

                instance.$mount();

                Vue.$dialog.app.$refs.container.appendChild(instance.$el);

                $(MessageBox.getSelector()).on("hide.bs.modal", function (e) {

                    const dialogResult = instance.dialogResult == null ? "Cancel" : instance.dialogResult;
                    Vue.$dialog.app.$refs.container.removeChild(instance.$el);
                    opt.onclose.call(this, dialogResult);

                });

                $(MessageBox.getSelector()).modal("show");

            }
        };

    }

};

export default MessageBox;
