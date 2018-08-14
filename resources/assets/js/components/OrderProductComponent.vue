<template>
    <section>
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- Search Bar -->
                <div class="form-inline form-group">
                    <input type="text" class="form-control" v-on:input="fetchOrderProduct()" v-model="search" placeholder="Search Product Name">
                    <span v-show="search" class="glyphicon glyphicon-remove" aria-hidden="true" @click="clearSearch()"></span>
                    <span v-show="dateRangeValue" class="glyphicon glyphicon-remove pull-right" aria-hidden="true" @click="clearSearchDate()"></span>
                        <el-date-picker class="pull-right"
                            v-model="dateRangeValue"
                            type="daterange"
                            align="right"
                            unlink-panels
                            :clearable="false"
                            range-separator="To"
                            start-placeholder="Start date"
                            end-placeholder="End date"
                            @input="fetchOrderProduct()"
                            :picker-options="pickerOptions2">
                        </el-date-picker>
                </div>
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
                        <tr v-if="Object.keys(noResult).length > 0">
                            <td class="text-center" colspan="4">No Results Found</td>
                        </tr>
                        <tr v-for="orderProduct in orderProducts" :key="orderProduct.id">
                            <td>{{orderProduct.name}}</td>
                            <td>{{orderProduct.description}}</td>
                            <td>{{orderProduct.quantity}}</td>
                            <td class="bg-warning">PHP {{orderProduct.income}}</td>
                        </tr>
                    </tbody>
                     <tfoot>
                        <tr>
                          <td class="bg-success"> </td>
                          <td class="bg-success"> </td>
                          <td class="bg-success"> </td>
                          <td class="bg-success"><strong>Total </strong> PHP {{ total }}</td>
                        </tr>
                    </tfoot>
                </table>
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
                 pickerOptions2: {
                  shortcuts: [{
                    text: 'Last week',
                    onClick(picker) {
                      const end = new Date();
                      const start = new Date();
                      start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                      picker.$emit('pick', [start, end]);
                    }
                  }, {
                    text: 'Last month',
                    onClick(picker) {
                      const end = new Date();
                      const start = new Date();
                      start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                      picker.$emit('pick', [start, end]);
                    }
                  }, {
                    text: 'Last 3 months',
                    onClick(picker) {
                      const end = new Date();
                      const start = new Date();
                      start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                      picker.$emit('pick', [start, end]);
                    }
                  }]
                },
                dateRangeValue: ''
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
            clearSearchDate:function(){
                this.dateRangeValue = '',
                this.fetchOrderProduct();
            },
            /*
            fetchOrderProduct: async function(){
                let vm = this;
                page_url = page_url || 'api/order-products?q=' + vm.search + '&d='+ vm.dateRangeValue
                try {
                    let response = await axios.get(page_url)
                    if(response.data.error){
                        vm.noResult = response.data.error;
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
            */
            fetchOrderProduct: async function(){
                let vm = this;
                let page_url = 'api/order-products?q=' + vm.search + '&d='+ vm.dateRangeValue
                await axios.get(page_url)
                .then( response => {
                    response.data.error ? vm.noResult = response.data.error : vm.noResult = '';
                    vm.orderProducts = response.data.data;
                    vm.total = response.data.total;
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
