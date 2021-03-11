<template>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> #</a>
        </div>
        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Utilisateurs</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{users_nbr}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Décrets</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{folders_nbr}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Documents</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{documents_nbr}}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
           
            <div class="col-sm-12">
                <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recherche Rapide</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <form class="col-sm-4">
                        <div class="form-group">
                            <label class="text-info">RECHERCHE PAR NOM PRENOM</label>
                            <input type="text" class="form-control" v-model="search_fullname" placeholder="Tapez le nom et prénom...">
                            <button type="button" class="btn btn-info btn-round btn-default mt-2" @click.prevent="searchByFullName" :disabled="loading">
                                <template v-if="!loading">RECHERCHE</template>
                                <template v-if="loading"><i class='fa fa-spin fa-spinner'></i> PATIENTEZ...</template>
                            </button>
                        </div>
                    </form>
                    <form class="col-sm-4">
                        <div class="form-group">
                            <label class="text-info">RECHERCHE PAR N° DECRET</label>
                            <input type="text" class="form-control" v-model="search_decret" placeholder="Tapez le N° du décret...">
                            <button type="button" class="btn btn-info btn-round btn-default mt-2" @click.prevent="searchByDecret" :disabled="loading">
                                <template v-if="!loading">RECHERCHE</template>
                                <template v-if="loading"><i class='fa fa-spin fa-spinner'></i> PATIENTEZ...</template>
                            </button>
                        </div>
                    </form>
                    <form class="col-sm-4">
                        <div class="form-group">
                            <label class="text-info">RECHERCHE PAR ANNEE DU DECRET</label>
                            <input type="text" class="form-control" v-model="search_datedecret" placeholder="Tapez l'année du décret...">
                            <button type="button" class="btn btn-info btn-round btn-default mt-2" @click.prevent="searchByDateDecret" :disabled="loading">
                                <template v-if="!loading">RECHERCHE</template>
                                <template v-if="loading"><i class='fa fa-spin fa-spinner'></i> PATIENTEZ...</template>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <h4 class="mt-5 text-center">Résultat de la recherche</h4>
                    <table class="table table-bordered dataTable mt-5" id="" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>N° DECRET</th>
                            <th>DATE</th>
                            <th>DECORATION</th>
                            <th>STRUCTURE</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>N° DECRET</th>
                            <th>DATE</th>
                            <th>DECORATION</th>
                            <th>STRUCTURE</th>
                            <th>ACTIONS</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <tr class="text-center" v-show="results.length" v-for="(result, index) in results" :key="result.id">
                            <td class="align-middle">{{result.number}}</td>
                            <td class="align-middle">
                                <p>Décret : {{result.date_decret}}</p>
                                <p>Décoration : {{result.date_decoration}}</p>
                            </td>
                            <td class="align-middle">
                                <p>Décoration : {{result.decoration}}</p>
                                <p>Grade : {{result.grade}}</p>
                            </td>
                            <td class="align-middle">{{result.structure}}</td>
                            <td class="align-middle">
                                <a :href="result.file" class="btn btn-primary btn-sm" download=""><i class="fa fa-download"></i> Télécharger le décret</a>
                                <a :href="result.file" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-eye"></i> Voir le décret</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        props:['users_nbr','folders_nbr','documents_nbr'],
        data () {
            return {
                errors: [],
                search:'',
                search_fullname:'',
                search_decret:'',
                search_datedecret:'',
                results:[],
                loading:false,
                where:''
            }
        },
        mounted() {
        },
        methods: {
            searchByFullName(){
                this.search_decret = "";
                this.search_datedecret = "";
                this.search = this.search_fullname;
                this.where = "description";
                this.loading = true;
                this.results = [];
                if(this.search.length > 0){
                    axios.get('/search/fullname/',{params: {where: this.where,search: this.search}}).then(response => {
                        this.results = response.data;
                        console.log(response.data);
                        this.loading = false;
                    });
                }
            },
            searchByDecret(){
                this.search_fullname = "";
                this.search_datedecret = "";
                this.search = this.search_decret;
                this.where = "number";
                this.loading = true;
                this.results = [];
                if(this.search.length > 0){
                    axios.get('/search/decret',{params: {where: this.where,search: this.search}}).then(response => {
                        this.results = response.data;
                        this.loading = false;
                    });
                }
            },
            searchByDateDecret(){
                this.search_decret = "";
                this.search_fullname = "";
                this.search = this.search_datedecret;
                this.where = "date_decret";
                this.loading = true;
                this.results = [];
                if(this.search.length > 0){
                    axios.get('/search/datedecret',{params: {where: this.where,search: this.search}}).then(response => {
                        this.results = response.data;
                        this.loading = false;
                    });
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
