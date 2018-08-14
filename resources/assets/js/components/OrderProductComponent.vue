<template>
    <section>
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- Search Bar -->
                <div class="form-inline form-group">
                    <input type="text" class="form-control" v-on:input="fetchOrderProduct()" v-model="search" placeholder="Search Product Name">
                    <span v-show="search" class="glyphicon glyphicon-remove" aria-hidden="true" @click="clearSearch()"></span>
                   
                </div>
                
                   <span class="bg-success"> Total Overall Income </span> <strong>PHP {{ total }}</strong>
                    <hr>
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th class="col-md-2">Product Name</th>
                            <th class="col-md-2">Description</th>
                            <th class="col-md-2">Number of Sold</th>
                            <th class="col-md-2">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div v-if="Object.keys(noResult).length > 0">
                            <span class="alert-danger col-md-12">No Results Found</span>
                        </div>
                        <tr v-for="orderProduct in orderProducts" :key="orderProduct.id">
                            <td>{{orderProduct.name}}</td>
                            <td>{{orderProduct.description}}</td>
                            <td>{{orderProduct.quantity}}</td>
                            <td class="bg-success">PHP {{orderProduct.income}}</td>
                        </tr>
                    </tbody>
                </table>

            <small class="pull-right">{{pagination.current_page}} of {{pagination.last_page}}</small>
            <nav aria-label="...">
                <ul class="pager">
                    <li :class="[{disabled: !pagination.prev_page_url}]" ><a href="#" @click="!!pagination.prev_page_url && fetchOrderProduct(pagination.prev_page_url)">Previous</a></li>
                    <li :class="[{disabled: !pagination.next_page_url}]" ><a href="#" @click="!!pagination.next_page_url && fetchOrderProduct(pagination.next_page_url)">Next</a></li>
                </ul>
            </nav> 
            </div>
        </div>    
    </section>
</template>

<script>
    export default {
        data(){
            return {
                 orderProducts: {},
                 noResult:{},
                 total:'',
                 pagination:{},
                 search:'',
            }
        },

        created(){
            this.fetchOrderProduct();
        },

        methods: {
            clearSearch: function(){
                this.search = '',
                this.fetchOrderProduct();
            },
            fetchOrderProduct: async function(page_url){
                let vm = this;
                page_url = page_url || 'api/order-products?q=' + vm.search
                try {
                    let response = await axios.get(page_url)
                    if(response.data.error){
                        vm.noResult = response.data.error;
                        vm.pagination.current_page = 0;
                        vm.pagination.last_page = 0;
                    }else{
                        vm.noResult = '';
                    }
                    vm.orderProducts = response.data.data;
                    vm.total = response.data.total;
                    vm.makePagination(response.data.meta, response.data.links);
                }catch(err){
                    console.log(err)
                }
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
