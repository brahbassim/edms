<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Gestion des types de décorations</strong></h4>
        </div>
        <div class="row bg-white">
            <div class="col-sm-2 mt-2">
                <strong class="">Recherche : </strong>
            </div>
            <div class="col-sm-3">
                <select v-model="queryFiled" class="form-control">
                    <option v-for="q in queryOptions" :value="q.field">{{q.value}}</option>
                </select>
            </div>
            <div class="col-sm-7">
                <div class="form-group">
                    <div class="input-group mb-0">
                        <input v-model="query" @keyup.enter="searchDecorations" type="text" class="form-control" placeholder="Rechercher...">
                        <div class="input-group-append">
                            <span class="input-group-text" @click.prevent="searchDecorations"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bg-white mt-4 mb-4">
            <div class="col-sm-12">
                <button type="button" class="btn  btn-primary mb-4 float-right" @click="create">Ajouter un type <i class="fa fa-user"></i></button>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>NOM</th>
                            <th>DESCRIPTION</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center" v-show="decorations.length" v-for="(decoration, index) in decorations" :key="decoration.id">
                            <td class="align-middle">{{ decoration.name}}</td>
                            <td class="align-middle">{{ decoration.description}}</td>
                            <td class="align-middle">
                                <button @click.prevent="edit(decoration)" class="btn btn-warning btn-sm" style="width:30px;"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" style="margin-top: 5px;" id="decoration-store" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header mb-4">
                        <h4 v-if="!isEditing" class="title largeModalLabel">Création d'un type</h4>
                        <h4 v-if="isEditing" class="title largeModalLabel">Modifier un type</h4>
                        <button type="button" class="close" @click.prevent="resetForm" aria-label="Fermer" :disabled="loading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nom <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.name" class="form-control" placeholder="Entrer le nom" />
                                    <span class="text-danger" v-if="getErrors.name">
                                        {{ getErrors.name[0] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                         <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea v-model="form.description" class="form-control" placeholder="Entrer la description" ></textarea>
                                    <span class="text-danger" v-if="getErrors.description">
                                        {{ getErrors.description[0] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="isEditing" type="button" class="btn btn-success btn-round btn-default" @click.prevent="save" :disabled="loading">
                            <template v-if="!loading">METTRE À JOUR</template>
                            <template v-if="loading"><i class='fa fa-spin fa-spinner'></i> EN COURS DE MISE À JOUR...</template>
                        </button>
                        <button v-if="!isEditing" type="button" class="btn btn-default btn-round btn-success" @click.prevent="save" :disabled="loading">
                            <template v-if="!loading">SAUVEGARDER</template>
                            <template v-if="loading"><i class='fa fa-spin fa-spinner'></i> EN COURS DE SAUVEGARDE...</template>
                        </button>
                        <button type="button" class="btn btn-warning btn-simple btn-round" @click.prevent="resetForm" :disabled="loading">ANNULER</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data () {
            return {
                errors: [],
                queryOptions: [{field: 'name', value: 'Nom'},{field: 'description', value: 'Description'}],
                queryFiled: 'name',
                query:'',
                decorations: [],
                form:{name: '',description:''},
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
            }
        },
        mounted() {
            this.fetchDecorations();
        },
        methods: {
            fetchDecorations(){
                axios.get('/decorations/?page='+this.pagination.current_page).then(response => {
                    this.decorations = response.data.data;
                }).catch(error => {
                    this.toast(error);
                })
            },
            searchDecorations(){
                if (this.query === ''){
                    toastr['warning']('Le champ recherche est requit!', 'Oops', {timeOut: 5000, closeButton: true});
                } else {
                    this.$Progress.start();
                    axios.get('/decorations/search/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page).then(response => {
                        this.decorations = response.data.data;
                        this.$Progress.finish();
                    }).catch(error => {
                        this.$Progress.finish();
                        this.toast(error);
                    })
                }
            },
            save(){
                let url = '';
                if(!this.isEditing){
                    url = '/decorations/nouveau';
                }else {
                    url = '/decorations/'+this.form.id+'/edition';
                }
                this.loading = true;
                this.errors = [];
                let formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('description', this.form.description);
                axios.post(url, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.resetForm();
                    toastr['success'](this.isEditing ? "Le type a bien été créé" : "Le type a bien été mis à jour", '', {timeOut: 5000, closeButton: true});
                    this.loading = false;
                    this.isEditing = false;
                    this.fetchDecorations();
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            edit(decoration){
                this.isEditing = true;
                this.form = decoration;
                $('#decoration-store').appendTo('body').modal('show');
            },
            reload() {
                this.query = '';
                this.queryFiled = 'name';
                this.$Progress.start();
                this.fetchDecorations();
                this.$Progress.finish();
            },
            create(){
                $('#decoration-store').appendTo('body').modal('show');
            },
            destroy(decoration){
                swal({
                    title: "Êtes-vous sûr?",
                    text: "Vous ne pourrez pas récupérer ce type après supression!",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }, () => {
                    axios.get('/decorations/'+decoration.id+'/supression').then(response => {
                        this.fetchDecorations();
                        toastr['success']("Le type a bien été supprimé", '', {timeOut: 5000, closeButton: true});
                    }).catch(error => {
                        this.toast(error);
                    });
                });
            },
            toast(error){
                if(error.response.status === 422){
                    this.errors = error.response.data.errors;
                }else if(error.response.status === 403){
                    toastr['warning'](error.response.data.message, 'Vous n\'êtes pas autorisé!', {timeOut: 5000, closeButton: true});
                }else{
                    toastr['error']('Une érreur est survenue!', 'Réessayez plus tard...', {timeOut: 5000, closeButton: true});
                }
            },
            resetForm(){
                
                this.form.name = '';
                
                this.fetchDecorations();
                $('#decoration-store').modal('hide');
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
