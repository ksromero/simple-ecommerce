require('./bootstrap');

Vue.component('products', require('./components/ProductComponent.vue'));
Vue.component('display-products', require('./components/DisplayProducts.vue'));
const app = new Vue({
    el: '#app'
});
