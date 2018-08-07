require('./bootstrap');

Vue.component('products', require('./components/ProductComponent.vue'));
Vue.component('employees', require('./components/EmployeesComponent.vue'));
Vue.component('orders', require('./components/OrdersComponent.vue'));
Vue.component('display-products', require('./components/DisplayProducts.vue'));



const app = new Vue({
    el: '#app'
});
