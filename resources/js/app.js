/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import VueRouter from 'vue-router'
import router from './routes'
import App from './App.vue';

import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import axios from 'axios'
import VueAxios from 'vue-axios'
import User from './Helpers/User';
import VoerroTagsInput from '@voerro/vue-tagsinput';
import VueAgile from 'vue-agile';

window.Vue = require('vue');

Vue.router = router
Vue.use(VueRouter);
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.use(VueAxios, axios)
Vue.use(require('vue-moment'));
Vue.use(VueAgile);
window.User = User;
Vue.component('tags-input', VoerroTagsInput);
Vue.component('pagination', require('laravel-vue-pagination'));
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
});
