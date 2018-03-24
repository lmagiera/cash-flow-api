
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

Vue.prototype.$http = axios;

const app = new Vue({

    el: '#app',

    methods: {

        addTransaction: function(data) {

            console.log("Adding Transaction with data: " + data.transaction);


        }
    },
    components: {
        'tool-bar': require('./components/ToolBar.vue')
    }

});
