<template>
    <section>
        <!-- Search Bar -->
        <div class="form-inline form-group">
            <input type="text" class="form-control" v-on:input="fetchOrders()" v-model="search" placeholder="Search Product Name">
            <span v-show="search" class="glyphicon glyphicon-remove" aria-hidden="true" @click="clearSearch()"></span>
        </div>
        <!-- Modal -->
        <modal v-model="orderModal" :header="false" size="lg" :dismiss-btn="false">
            <div class="well well-sm" id="no-padding-top">
                <p class="text-primary h3">{{order.customer && order.customer.name}}</p>
                <span>{{order.customer && order.customer.email}}</span>
            </div>

            <div class="well well-sm">
                <p class="h4"><span class="fa fa-briefcase"></span> Order Information</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-2 col-lg-2">Date</th>
                            <th class="col-md-2 col-lg-2">Customer</th>
                            <th class="col-md-2 col-lg-2">Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ order.created_at }}</td>
                            <td>{{ order.customer && order.customer.name }}</td>
                            <td>Paypal</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="bg-warning">Subtotal</td>
                            <td class="bg-warning">{{ order.sub_total }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="bg-warning">Discount</td>
                            <td class="bg-warning">{{ order.discount }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="bg-success"><strong>Total</strong></td>
                            <td class="bg-success">{{ order.total_price }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="well well-sm">
                <p class="h4"><span class="fa fa-opencart"></span> Items</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-2 col-lg-2">Name</th>
                            <th class="col-md-2 col-lg-2">Description</th>
                            <th class="col-md-2 col-lg-2">Quantity</th>
                            <th class="col-md-2 col-lg-2">Price</th>
                            <th class="col-md-2 col-lg-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                            <td>{{order.product && order.product.name}}</td>
                            <td>{{order.product && order.product.description}}</td>
                            <td>{{order.quantity}}</td>
                            <td>{{order.sub_total}}</td>
                            <td><span class="fa fa-check"></span></td>
                    </tbody>
                </table>
            </div>
        </modal>

        <div class="well well-lg">
            <div class="row">
                <div class="panel panel-info" v-for="order in orders" :key="order.id" style="margin-top:15px;"> 
                    <div class="panel-heading">
                        <a href="#" @click="fetchOrder(order.id)" class="panel-title"><u>{{ order.created_at }}</u></a>
                    </div>

                    <div class="panel-body">
                        Customer : {{order.customer.name}} <br>
                        Total: <span class="alert-success">{{order.total_price}}</span>
                    </div>
                </div>
            </div>
        </div>

        
    </section>
    <!-- <img :src="'http://localhost/eapp/public/cover_images/' + product.cover_image" alt="..."> -->
</template>

<script>
   export default {
     data(){
       return{
         orders:{},
         order:{},
         pagination: {},
         noResult: {},
         search:'',
         orderModal: false
       }
     },

     created(){
       this.fetchOrders();
     },

     methods: {
       fetchOrders: async function(page_url){
          let vm = this;
          page_url = page_url || 'api/orders?q=' + vm.search
          try {
              let response = await axios.get(page_url)
              if(response.data.error){
                  vm.noResult = response.data.error;
                  vm.pagination.current_page = 0;
                  vm.pagination.last_page = 0;
              }else{
                  vm.noResult = '';
              }
              vm.orders = response.data.data;
              vm.makePagination(response.data.meta, response.data.links);
          }catch(err){
              console.log(err)
          }
        },
        fetchOrder: function(id){
            let vm = this;
            axios.get('api/order/' + id)
            .then( response => {
                vm.order = response.data.data;
                vm.orderModal = true;
            })
            .catch( error => {
                console.log(error);
            });
        },
        makePagination: function(meta, links){
          let pagination = {
              current_page: meta.current_page,
              last_page: meta.last_page,
              next_page_url: links.next,
              prev_page_url: links.prev
          };
          this.pagination = pagination;
       },
     }
   }
</script>
<style scoped>
    #no-padding-top{
        padding-top:0px;
    }
    .well{
        background-color:white;
    }
</style>
