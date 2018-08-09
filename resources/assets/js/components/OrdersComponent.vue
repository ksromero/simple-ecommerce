<template>
    <section>
        <!-- Search Bar -->
        <div class="form-inline form-group">
            <input type="text" class="form-control" v-on:input="fetchOrders()" v-model="search" placeholder="Search Customer Name">
            <span v-show="search" class="glyphicon glyphicon-remove" aria-hidden="true" @click="clearSearch()"></span>
        </div>
        <!-- Modal -->
        <modal v-model="orderModal" :header="false" size="lg" :dismiss-btn="false" :footer="false">
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
                        <tr v-for="items in order.items" :key="items.id">
                            <td>{{items.name}}</td>
                            <td>{{items.description}}</td>
                            <td>{{items.pivot.quantity}}</td>
                            <td>{{items.price}}</td>
                            <td><span class="fa fa-check bg-success"></span></td>
                        </tr>                    
                    </tbody>
                </table>
            </div>
        </modal>

        <div class="well well-lg">
            <div v-if="Object.keys(noResult).length > 0">
                <span class="alert-danger">No Results Found</span>
            </div>
             <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th class="col-md-2">Date</th>
                            <th class="col-md-2">Customer</th>
                            <th class="col-md-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div v-if="Object.keys(noResult).length > 0">
                            <span class="alert-danger col-md-12">No Results Found</span>
                        </div>
                        <tr v-for="order in orders" :key="order.id">
                            <td><a href="#" @click="fetchOrder(order.id)" class="panel-title"><u>{{ order.created_at }}</u></a></td>
                            <td>{{order.customer.name}}</td>
                            <td>{{order.total_price}}</td>
                        </tr>
                    </tbody>
                </table>
            <small class="pull-right">{{pagination.current_page}} of {{pagination.last_page}}</small>
            <nav aria-label="...">
                <ul class="pager">
                    <li :class="[{disabled: !pagination.prev_page_url}]" ><a href="#" @click="!!pagination.prev_page_url && fetchOrders(pagination.prev_page_url)">Previous</a></li>
                    <li :class="[{disabled: !pagination.next_page_url}]" ><a href="#" @click="!!pagination.next_page_url && fetchOrders(pagination.next_page_url)">Next</a></li>
                </ul>
            </nav> 
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
        clearSearch: function(){
            this.search = '',
            this.fetchOrders();
        },
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
