<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Statistiques</strong></h4>
        </div>
        <div class="row bg-white">
            <div class="col-sm-2 mt-2">
                <strong class="">Choisir une décoration : </strong>
            </div>
            <div class="col-sm-5">
                <multiselect   placeholder="Choisir un type de décoration" v-model="form.category_id"  track-by="id" label="name" :options="categories"></multiselect>
            </div>
            <div class="col-sm-4">
                <input type="text" placeholder="Année de décoration (Facultative)" v-model="form.date" class="form-control">
            </div>
            <div class="col-sm-1">
                <div class="input-group-append">
                    <span class="input-group-text" @click.prevent="fetchStats"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div class="row bg-white mt-4 mb-4">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <h4 v-show="grades.total > 0">Décoration : <span class="text-info">{{form.category_id.name}}</span> avec un total de <span class="text-info">{{grades.total}}</span></h4>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>GRADE</th>
                            <th>NOMBRE</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" v-show="grades.data.length" v-for="(grade, index) in grades.data" :key="grade.id">
                                <td class="align-middle">{{ grade.grade}}</td>
                                <td class="align-middle">{{ grade.number}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        props:['categories_data'],
        data () {
            return {
                errors: [],
                decorations: [],
                pagination:{current_page: 1},
                form:{category_id:'',date:''},
                loading: false,
                isEditing:false,
                categories:[],
                grades:[]
            }
        },
        mounted() {
            this.categories = this.categories_data;
        },
        methods: {
            fetchStats(){
                let currentdate = null;
                if(this.form.date != ''){
                    currentdate = this.form.date;
                }
                axios.get('/statistiques/'+this.form.category_id.id+'/'+currentdate+'/search').then(response => {
                    this.grades = response.data;
                }).catch(error => {
                    this.toast(error);
                })
            },
            toast(error){
                if(error.response.status === 422){
                    this.errors = error.response.data.errors;
                }else if(error.response.status === 403){
                    toastr['warning'](error.response.data.message, 'Vous n\'êtes pas autorisé!', {timeOut: 5000, closeButton: true});
                }else{
                    toastr['error']('Une érreur est survenue!', 'Réessayez plus tard...', {timeOut: 5000, closeButton: true});
                }
            }
        },
        computed: {
            getErrors() {
                return this.errors;
            }
        }
    }
</script>

<style>
    .alert{
        border-radius: 0px;
    }
</style>
