import Vue from 'vue';
import axios from 'axios';
import * as uiv from 'uiv'

window.Popper = require('popper.js').default;
window.Vue = Vue;
window.axios = axios;
Vue.use(uiv)

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}

axios.defaults.baseURL = 'http://localhost/eapp/public/';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
