<template>
    <div>
      <div v-if="success" class="alert alert-success fade show">
        <h4 class="alert-heading">Done Successfully</h4>
        {{success}}
      </div>
      <div v-if="error" class="alert alert-danger fade show">
        <h4 class="alert-heading">Error</h4>
        {{error}}
      </div>
      <template v-if="drugList.length > 0">
        <form id="search_drug_form" style="position: relative" class="mb-3">
            <input type="text" v-model.lazy.trim="search" @blur="searchDrug" autocomplete="off" name="search_drug" id="search_drug" placeholder="search for a medicine" class="form-control" value="">
            <button class="search" type="submit" @click.prevent="searchDrug">
            <span class="glyphicon glyphicon-search"></span>
            </button>
        </form>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody id="drug_table">
            <tr v-for="(drug, index) in drugList" :key="index">
                <td>{{index+1}}</td>
                <td>{{drug.name}}</td>
                <td><a :href="'/medication/system/edit/'+drug.id" class="btn btn-secondary">edit <span class="glyphicon glyphicon-edit"></span></a></td>
                <td><button @click.prevent="deleteDrug(drug.id)" class="btn delete_system_drug btn-danger">delete <span class="glyphicon glyphicon-trash"></span></button></td>
            </tr>
            </tbody>
        </table>
      </template>
      <div v-else class="alert alert-warning">There is no medications created on the system</div>
    </div>
</template>

<script>
    axios = require('axios');
    export default {
        data:function(){
            return {
                search: '',
                drugList: [],
                error: '',
                success: ''
            }
        },
        methods: {
            fetchDrugs: function(){
                axios.get('/medication/system/all/json').then(response => {
                    this.drugList = response.data.drugs;
                }).catch(err => {console.log(err)})
            },
            searchDrug: function(){
                axios.post('/medication/system/search',{ search_drug: this.search}).then(response =>{
                    if(response.data.state == 'OK'){
                        this.drugList = response.data.drugs;
                        this.error = '';
                    }else{
                        this.error = response.data.error;
                        this.success = '';
                        this.fetchDrugs();
                    }
                    setTimeout(() => {
                        this.error = '';
                        this.success = '';
                    }, 10000);
                }).catch(err => {console.log(err)})
            },
            deleteDrug: function(id){
                axios.delete('/medication/system/delete/'+id).then(response => {
                    this.success = response.data.success;
                    this.fetchDrugs();
                }).catch(err => {console.log(err)});
            }
        },
        created: function(){
            this.fetchDrugs();
        }
    }
</script>
