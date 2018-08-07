<template>
    <section>
      <div class="page-header">
            <h1 id="product"><small>Products</small></h1>
        </div>
        <!-- Search Bar -->
        <div class="form-inline form-group">
            <input type="text" class="form-control" v-on:input="fetchProducts()" v-model="search" placeholder="Search Product Name">
            <span v-show="search" class="glyphicon glyphicon-remove" aria-hidden="true" @click="clearSearch()"></span>
        </div>

        <div class="well well-lg">
            <div v-if="Object.keys(noResult).length > 0">
                <span class="alert-danger">No Results Found</span>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4" v-for="product in products" :key="product.id">
                <div class="thumbnail">
                    <img class="thumbnail" :src="'http://localhost/eapp/public/cover_images/' + product.cover_image" width="250" height="250" style="margin-top:20px;" alt="...">
                    <div class="caption">
                        <h3>{{ product.name }}</h3>
                        <hr>
                        <p><small>Price &#8369;{{product.price}}</small></p>
                        <form method="POST" :action="cart" class="form-inline pull-right">
                            <input type="hidden" name="_token" :value="csrf">
                            <input type="hidden" name="id" :value="product.id">
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>Add to Cart</button>
                        </form>
                            <button type="button" class="btn btn-default" @click="openModal(product.id)"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Quick View</button>
                    </div>
                </div>
                </div>
            </div>

            <small class="pull-right">{{pagination.current_page}} of {{pagination.last_page}}</small>
            <nav aria-label="...">
                <ul class="pager">
                    <li :class="[{disabled: !pagination.prev_page_url}]" ><a href="#product" @click="!!pagination.prev_page_url && fetchProducts(pagination.prev_page_url)">Previous</a></li>
                    <li :class="[{disabled: !pagination.next_page_url}]" ><a href="#product" @click="!!pagination.next_page_url && fetchProducts(pagination.next_page_url)">Next</a></li>
                </ul>
            </nav> 
      </div><!-- end of well -->

       <modal v-model="productModal" size="md" :header="false" :dismiss-btn="false">
            <div class="row">
              <div class="col-md-6">
                  <img class="img-responsive" :src="'http://localhost/eapp/public/cover_images/'+ product.cover_image">
              </div>
              <div class="col-md-6">
                <strong>{{product.name}}</strong><br>
                <small>&#8369;{{product.price}}</small>
                <hr>
                {{product.description}}<br>
              </div>
             
            </div>

          <div slot="footer">
             <form method="POST" :action="cart" class="form-inline pull-right">
                  <input type="hidden" name="_token" :value="csrf">
                  <input type="hidden" name="id" :value="product.id">
                  <div class="form-group">
                      <input type="text" name="qty" class="form-control" placeholder="Quantity">               
                  </div>
                    <button type="submit" class="btn btn-warning">Add to Cart</button>
              </form>     
          </div>
        </modal>
    </section>
    <!-- <img :src="'http://localhost/eapp/public/cover_images/' + product.cover_image" alt="..."> -->
</template>

<script>
   export default {
    props:['route','cart'],
     data(){
       return{
         csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
         products:{},
         product:{},
         pagination: {},
         noResult: {},
         search:'',
         productModal:false
       }
     },

     created(){
       this.fetchProducts();
     },

     methods: {
      openModal : function(id){
            let vm = this;
            axios.get('api/products/' + id)
            .then( response => {
                vm.product = response.data.data;
                vm.productModal = true;
            })
            .catch( error => {
                console.log(error);
            });
       },
       closeModal: function(){
            for (var key in this.product ) {
               this.product[key] = null;
            }
            this.productModal = false;
       },
       clearSearch: function(){
            this.search = '',
            this.fetchProducts();
        },
       fetchProducts: async function(page_url){
          let vm = this;
          page_url = page_url || 'api/products?q=' + vm.search
          try {
              let response = await axios.get(page_url)
              if(response.data.error){
                  vm.noResult = response.data.error;
                  vm.pagination.current_page = 0;
                  vm.pagination.last_page = 0;
              }else{
                  vm.noResult = '';
              }
              vm.products = response.data.data;
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
