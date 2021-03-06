
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



require("./bootstrap");

/* global Vue, axios */
window.Vue = require("vue");


/**
 * Load Message Box plugin
 */
import MessageBox from "./components/MessageBox.plugin.js";
/**
 * Load AirbnbStyleDatepicker
 */
import AirbnbStyleDatepicker from "vue-airbnb-style-datepicker";
import "vue-airbnb-style-datepicker/dist/styles.css";

Vue.use(MessageBox);

const datepickerOptions = {};
window.Vue.use(AirbnbStyleDatepicker, datepickerOptions);

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

// install notifier
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

    components: {
        "tool-bar": require("./components/ToolBar.vue"),
        "transaction-list": require("./components/TransactionList.vue"),
        "date-range-selector": require("./components/DateRangeSelector.vue"),

    },

    mounted() {

        Vue.$dialog.app = this;

    }

});






