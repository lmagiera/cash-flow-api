
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('date-selector', require('./components/DateSelector.vue'));
Vue.component('user-saldo', require('./components/UsersSaldo.vue'));
Vue.component('cash-flow-graph', require('./components/CashFlowGraph.vue'));

Object.defineProperty(Vue.prototype, '$bus', {
    get() {
        return this.$root.bus;
    }
});

let bus = new Vue({});

Vue.prototype.HTTP = axios.create({

    baseURL: 'http://cash-flow-api.a6.net/api/',
    headers: {

    }
});

const app = new Vue({

    el: '#app',

    data() {
        return {
            bus: bus,
            HTTP: this.HTTP
        }

    },

    methods: {

        addTransaction: function(data) {

            console.log("Adding Transaction with data: " + data.transaction);


        }
    },
    components: {
        'tool-bar': require('./components/ToolBar.vue'),
        'transaction-list': require('./components/TransactionList.vue')
    }

});
