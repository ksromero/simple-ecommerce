import Vue from 'vue';
import axios from 'axios';
import * as uiv from 'uiv'
import ElementUI from 'element-ui';
import '../sass/element-variables.scss'
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

window.Popper = require('popper.js').default;
window.Vue = Vue;
window.axios = axios;
Vue.use(uiv)
Vue.use(ElementUI)

locale.use(lang)

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
