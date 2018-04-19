
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



require("./bootstrap");

/* global Vue, axios */
window.Vue = require("vue");


import MessageBox from "./components/MessageBox.plugin.js";

Vue.use(MessageBox);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// TODO: should check if safari ...
window.onbeforeunload=function(e){};

let bus = new Vue({});

Object.defineProperty(Vue.prototype, "$bus", {
    get() {
        return this.$root.bus;
    }
});

let notifier = new Vue({
    methods: {
        danger(message) {
            $.notify({message}, {"type": "danger"});
        },
        success(message) {
            $.notify({message}, {"type": "success"});
        }
    }
});

Object.defineProperty(Vue.prototype, "$notifier", {
    get() {
        return this.$root.notifier;
    }
});

Vue.prototype.HTTP = axios.create({

    baseURL: process.env.MIX_API_ENDPOINT,
    headers: {}
});



const app = new Vue({

    el: "#app",

    data() {
        return {
            bus,
            notifier,
            "HTTP": this.HTTP
        };

    },

    methods: {

        addTransaction(data) {
            //console.log("Adding Transaction with data: " + data.transaction);
        },

        dialog() {

            Vue.$dialog.show({
                title: "Hello World",
                message: "This is a beautiful world!",
                type: "Ok",
                buttons: { Yes: {text: "Yes"}, No: {text: "No"}, Cancel: {text: "Cancel"}},
                onclose(dialogResult) {

                    alert(dialogResult);

                }
            });



        }

    },
    components: {
        "tool-bar": require("./components/ToolBar.vue"),
        "transaction-list": require("./components/TransactionList.vue"),
        "date-range-selector": require("./components/DateRangeSelector.vue"),

    },

    mounted() {

        Vue.$dialog.app = this;

    }

});






