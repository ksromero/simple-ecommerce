<template>
    <section>
         <!-- Modal of Products -->
        <btn type="primary" @click="employeeModal=true" style="margin-bottom:10px;" class="pull-right">Create Employee</btn>
        <form @submit.prevent="addEmployee" enctype="multipart/form-data">
            <modal v-model="employeeModal" title="Modal Title" size="md" :dismiss-btn="false">
                <div class="form-group">
                    <label>Employee Name</label>
                    <input type="text" v-model="employee.name" class="form-control" placeholder="Employee Name">
                    <span class="alert-danger" v-show="errors.has('name')" v-text="errors.get('name')"></span>
                </div>
                
                <div class="form-group">
                    <label>Employee Email</label>
                    <input type="email" class="form-control" v-model="employee.email" placeholder="Employee Email">
                    <span class="alert-danger" v-show="errors.has('email')" v-text="errors.get('email')"></span>
                </div>
                    
                <div class="form-group">
                    <label><span v-if="edit">New </span>Password</label>
                    <input type="text" class="form-control" v-model="employee.password" placeholder="xxxxxx">
                    <span class="alert-danger" v-show="errors.has('password')" v-text="errors.get('password')"></span>
                </div>
                
                <hr>
                <div slot="footer">
                    <button type="button" class="btn btn-default" @click="setFalse()">Close</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </modal>
        </form>
        <!-- Search Bar -->
        <div class="form-inline form-group">
            <input type="text" class="form-control" v-on:input="fetchEmployees()" v-model="search" placeholder="Search Employee Name">
            <span v-show="search" class="glyphicon glyphicon-remove" aria-hidden="true" @click="clearSearch()"></span>
        </div>
        <!-- Viewing of Employee -->
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-2 col-lg-2">Name of Employee</th>
                        <th class="col-md-2 col-lg-2">Email</th>
                        <th class="col-md-2 col-lg-2">Type</th>
                        <th class="col-md-2 col-lg-2">Created At</th>
                        <th class="col-md-2 col-lg-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                     <tr v-if="Object.keys(noResult).length > 0">
                        <td class="text-center" colspan="4">No Results Found</td>
                     </tr>
                    <tr v-for="employee in employees" :key="employee.id">
                        <td>{{ employee.name }}</td>
                        <td>{{ employee.email }}</td>
                        <td>{{ employee.role.role_name }}</td>
                        <td>{{ employee.created_at }}</td>
                        <td>
                            <button type="button" class="btn btn-link" @click="editEmployee(employee.id)"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</button>                          
                            <button type="button" class="btn btn-link" @click="deleteEmployee(employee.id)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
          </div>
          
        <small class="pull-right">{{pagination.current_page}} of {{pagination.last_page}}</small>
        <nav aria-label="...">
            <ul class="pager">
                <li :class="[{disabled: !pagination.prev_page_url}]" ><a href="#" @click="!!pagination.prev_page_url && fetchEmployees(pagination.prev_page_url)">Previous</a></li>
                <li :class="[{disabled: !pagination.next_page_url}]" ><a href="#" @click="!!pagination.next_page_url && fetchEmployees(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav> 
        </div>
        
    
    </section>
</template>

<script>
class Errors {
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
                employees: {},
                employee:{
                    name: '',
                    email: '',
                    password: '',
                    role: {}
                },
                noResult: {},
                pagination: {},
                search:'',
                edit:false,
                employeeModal: false,
                errors: new Errors()
            }
        },

        created(){
            return this.fetchEmployees();
        },

        methods: {
            clearSearch: function(){
                this.search = '',
                this.fetchEmployees();
            },
            setFalse:function(){
                this.employeeModal = false;
                this.edit = false;
                for (var key in this.employee ) {
                    this.employee[key] = null;

                }
                this.errors.clear();
            },
             fetchEmployees: async function(page_url){
                let vm = this;
                page_url = page_url || 'api/employees?q=' + vm.search
                try {
                    let response = await axios.get(page_url)
                    if(response.data.error){
                        vm.noResult = response.data.error;
                        vm.pagination.current_page = 0;
                        vm.pagination.last_page = 0;
                    }else{
                        vm.noResult = '';
                    }
                    vm.employees = response.data.data;
                    vm.makePagination(response.data.meta, response.data.links);
                }catch(err) {
                    console.log(err)
                }
            },
            addEmployee: function(){
                if(this.edit == false){
                    let vm = this;
                    axios.post('api/employees', vm.employee)
                    .then( response => {
                        alert("Employee Inserted");
                        vm.employee.name='';
                        vm.employee.email='';
                        vm.employee.password='';
                        vm.employeeModal = false;
                        vm.errors.clear();
                        vm.setFalse();
                        vm.fetchEmployees();
                    })
                    .catch( error => {
                        vm.errors.record(error.response.data);
                    });
                }else{
                    let vm = this;
                    axios.put('api/employees/'+vm.employee.id, vm.employee)
                    .then( response => {
                        alert("Employee Updated");
                        vm.employee.name='';
                        vm.employee.email='';
                        vm.employee.password='';
                        vm.employeeModal = false;
                        vm.errors.clear();
                        vm.setFalse();
                        vm.fetchEmployees();
                    })
                    .catch( error => {
                        vm.errors.record(error.response.data);
                    });
                }
            },
            editEmployee: function(id){
                let vm = this;
                axios.get('api/employees/' + id)
                .then( response => {
                    vm.edit = true;
                    vm.employee = response.data.data;
                    vm.employeeModal = true;
                })
                .catch( error => {
                    console.log(error);
                });
            },
            deleteEmployee: function(id){
                if(confirm('Are you sure?')){
                    let vm = this;
                    axios.delete('api/employees/' + id)
                    .then( response => {
                    alert("Employee Deleted");
                        vm.fetchEmployees();
                    })
                    .catch( error => {
                        console.log(error);
                    });
                }
            },
            makePagination: function(meta, links)
            {
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
    input{
        text-align:center;
    }
</style>