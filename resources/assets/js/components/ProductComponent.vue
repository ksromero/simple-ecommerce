<template>
    <section>
        <!-- Modal of Products -->
        <btn type="primary" @click="productModal=true" style="margin-bottom:10px;" class="pull-right">Create Product</btn>
        <form @submit.prevent="addProduct" enctype="multipart/form-data">
            <modal v-model="productModal" title="Modal Title" size="md" :dismiss-btn="false">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" v-model="product.name" class="form-control" placeholder="Product Name">
                    <span class="alert-danger" v-show="errors.has('name')" v-text="errors.get('name')"></span>
                </div>
                
                <div class="form-group">
                    <label>Product Description<small>(optional)</small></label>
                    <textarea class="form-control" v-model="product.description" rows="3"></textarea>
                </div>
                    
                <div class="form-group">
                    <label>Product Price</label>
                    <input type="text" class="form-control" v-model="product.price" placeholder="Product Price">
                    <span class="alert-danger" v-show="errors.has('price')" v-text="errors.get('price')"></span>
                </div>
                <hr>

                <div class="form-group">
                    <label for="exampleInputFile">Upload Image of Product</label>
                    <input type="file" ref="fileupload" v-on:change="onFileChange" id="exampleInputFile">
                    <span class="alert-danger" v-show="errors.has('cover_image')" v-text="errors.get('cover_image')"></span>
                </div>

                <div slot="footer">
                    <button type="button" class="btn btn-default" @click="setFalse()">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </modal>
        </form>

        <!-- Search Bar -->
        <div class="form-inline form-group">
            <input type="text" class="form-control" v-on:input="fetchProducts()" v-model="search" placeholder="Search Product Name">
            <span v-show="search" class="glyphicon glyphicon-remove" aria-hidden="true" @click="clearSearch()"></span>
        </div>

        <!-- Viewing of Products -->
        <div class="well">
            <div v-if="Object.keys(noResult).length > 0">
                <span class="alert-danger">No Results Found</span>
            </div>

            <div class="panel panel-info" v-for="product in products" :key="product.id" style="margin-top:15px;">
                <button type="button" class="btn btn-link pull-right" @click="deleteProduct(product.id)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</button>
                <button type="button" class="btn btn-link pull-right" @click="editProduct(product.id)"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</button>
                <div class="panel-heading">
                    <h3 class="panel-title">{{ product.name }}</h3>
                </div>

                <div class="panel-body">
                    <div class="col-xs-12 col-md-3">
                        <a href="#" class="thumbnail">
                            <img :src="'http://localhost/simple-ecommerce/public/cover_images/' + product.cover_image" alt="...">
                        </a>
                    </div>
                    <div style="margin-top:30px;">
                        {{ product.description }}<br>
                        Price: 	&#8369;{{ product.price }}<br>
                    </div>
                    <hr>                
                </div>

                <div class="panel-footer"><small>Created At {{ product.created_at }}</small></div>
            </div>
        </div>
        <small class="pull-right">{{pagination.current_page}} of {{pagination.last_page}}</small>
        <nav aria-label="...">
            <ul class="pager">
                <li :class="[{disabled: !pagination.prev_page_url}]" ><a href="#" @click="!!pagination.prev_page_url && fetchProducts(pagination.prev_page_url)">Previous</a></li>
                <li :class="[{disabled: !pagination.next_page_url}]" ><a href="#" @click="!!pagination.next_page_url && fetchProducts(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav> 
    </section>
</template>

<script>
class Errors 
{
    constructor(){
        this.errors = { };
    }

    //retrieve error message
    get(field){   
        if(this.errors[field])
        {
            return this.errors[field][0];
        }
    }
    //check if field has error
    has(field) {
        return this.errors.hasOwnProperty(field);
    }
    //record the errors
    record(errors){
        this.errors = errors.errors;
    }
    //check if there is error
    any() {
        return Object.keys(this.errors).length > 0;
    }
    //clear error
    clear(field) {
        if (field) {
            delete this.errors[field];
            return;
        }
        this.errors = {};
    }
}
    export default {
        data () {
            return {
                products: {},
                noResult: {},
                product: {
                    name:'',
                    description:'',
                    cover_image:'',
                    price:''
                },
                productModal:false,
                edit: false,
                search:'',
                pagination: {},
                errors: new Errors()
            }
        },

        created(){
            return this.fetchProducts();
        },

        methods: {
            onFileChange(e) {
                console.log(e.target.files[0]);
                let fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = (e) => {
                    this.product.cover_image = e.target.result
                };
            },

            setFalse:function(){
                let input = this.$refs.fileupload;
                this.productModal = false;
                input.type = 'text';
                input.type = 'file';
                this.edit = false;
                 for (var key in this.product ) {
                    this.product[key] = null;
                }
                this.errors.clear();
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

            addProduct: function(){
                if(this.edit == false){
                    let vm = this;
                    axios.post('api/products', vm.product)
                    .then( response => {
                        alert("Product Inserted");
                        vm.product.name='';
                        vm.product.description='';
                        vm.product.price='';
                        vm.product.cover_image='';
                        vm.productModal = false;
                        vm.errors.clear();
                        vm.setFalse();
                        vm.fetchProducts();
                    })
                    .catch( error => {
                        vm.errors.record(error.response.data);
                    });
                }else{
                    let vm = this;
                     axios.put('api/products/'+ vm.product.id, vm.product)
                    .then( response => {
                        alert("Product Updated");
                        vm.product.name='';
                        vm.product.description='';
                        vm.product.price='';
                        vm.productModal = false;
                        vm.errors.clear();
                        vm.fetchProducts();
                    })
                    .catch( error => {
                        vm.errors.record(error.response.data);
                    });
                }
            },

            editProduct: function(id){
                let vm = this;
                axios.get('api/products/' + id)
                .then( response => {
                    vm.edit = true;
                    vm.product = response.data.data;
                    vm.productModal = true;
                })
                .catch( error => {
                    console.log(error);
                });
            },

            deleteProduct: function(id){
                let vm = this;
                axios.delete('api/products/' + id)
                .then( response => {
                   alert("Product Deleted");
                    vm.fetchProducts();
                })
                .catch( error => {
                    console.log(error);
                });
            },
        }
    }
</script>
