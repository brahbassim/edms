<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Gestion des dossiers</strong></h4>
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
                        <input v-model="query" @keyup.enter="searchFolders" type="text" class="form-control" placeholder="Rechercher...">
                        <div class="input-group-append">
                            <span class="input-group-text" @click.prevent="searchFolders"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bg-white mt-4 mb-4">
            <div class="col-sm-12">
                <button type="button" class="btn  btn-primary mb-4 float-right" @click.prevent="create">Ajouter un dossier <i class="fa fa-folder"></i></button>
                <div class="table-responsive">
                    <table class="table table-bordered" style="min-height: 300px;" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>N° DECRET</th>
                            <th>DESCRIPTION</th>
                            <th>DATE DECRET</th>
                            <th>DATE DECORATION</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center" v-show="folders.length" v-for="(folder, index) in folders" :key="folder.id">
                            <td class="align-middle">{{ folder.number}}</td>
                            <td class="align-middle">{{folder.description}}</td>
                            <td class="align-middle">{{folder.date_decret}}</td>
                            <td class="align-middle">{{folder.date_decoration}}</td>
                            <td class="align-middle">
                                <button @click.prevent="edit(folder)" class="btn btn-warning btn-sm" style="width:30px;"><i class="fa fa-edit"></i></button>
                                <button @click.prevent="goFiles(folder.id)" class="btn btn-success btn-sm" ><i class="fa fa-file"></i> Gérer les  documents</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <pagination v-if="pagination.last_page >= 1"
                            :pagination="pagination"
                            :offset="5"
                            @paginate="query === '' ? fetchFolders() : searchFolders()"
                ></pagination>
            </div>
        </div>
        <div class="modal fade" style="margin-top: 5px;" id="folder-store" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header mb-4">
                        <h4 v-if="!isEditing" class="title largeModalLabel">Création d'un dossier</h4>
                        <h4 v-if="isEditing" class="title largeModalLabel">Modifier un dossier</h4>
                        <button type="button" class="close" @click.prevent="resetForm" aria-label="Fermer" :disabled="loading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nom <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.name" class="form-control" placeholder="Entrer le nom du dossier" />
                                    <span class="text-danger" v-if="getErrors.name">
                                        {{ getErrors.name[0] }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Réference <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.reference" class="form-control" placeholder="Entrer la reférence" />
                                    <span class="text-danger" v-if="getErrors.reference">
                                        {{ getErrors.reference[0] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12 text-center">
                                <label class="float-left">Description <span class="text-danger">*</span></label>
                                <textarea type="text" v-model="form.description" class="form-control" placeholder="Description" />
                                <span class="text-danger" v-if="getErrors.description">
                                        {{ getErrors.description[0] }}
                                </span>
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
    import Pagination from '../Pagination';
    export default {
        components: {
            Pagination
        },
        data () {
            return {
                errors: [],
                queryOptions: [{field: 'name', value: 'Nom'},{field: 'description' ,value: 'Description'},{field: 'reference' ,value: 'Réference'}],
                queryFiled: 'name',
                query:'',
                folders: [],
                form:{name: '',reference: '',description: ''},
                image: '',
                showPreview: false,
                imagePreview: '',
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
            }
        },
        mounted() {
            this.fetchFolders();
        },
        methods: {
            fetchFolders(){
                axios.get('/dossiers/?page='+this.pagination.current_page).then(response => {
                    this.folders = response.data.data;
                }).catch(error => {
                    this.toast(error);
                })
            },
            searchFolders(){
                if (this.query === ''){
                    toastr['warning']('Le champ recherche est requit!', 'Oops', {timeOut: 5000, closeButton: true});
                } else {
                    this.$Progress.start();
                    axios.get('/dossiers/search/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page).then(response => {
                        this.folders = response.data.data;
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
                    url = '/dossiers/nouveau';
                }else {
                    url = '/dossiers/'+this.form.id+'/edition';
                }
                this.loading = true;
                this.errors = [];
                let formData = new FormData();
                if(this.$refs.image){
                    formData.append('image', this.$refs.image.files[0] ? this.$refs.image.files[0] : '');
                }
                formData.append('name', this.form.name);
                formData.append('reference', this.form.reference);
                formData.append('description', this.form.description);
                console.log(this.form);
                axios.post(url, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.resetForm();
                    toastr['success'](this.isEditing ? "Le dossier a bien été créé" : "Le dossier a bien été mis à jour", '', {timeOut: 5000, closeButton: true});
                    this.loading = false;
                    this.isEditing = false;
                    this.fetchFolders();
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            edit(folder){
                window.location.replace('/dossiers/'+folder.id+'/edition');
                
                //this.isEditing = true;
                //this.form = folder;
                //$('#folder-store').appendTo('body').modal('show');
            },
            reload() {
                this.query = '';
                this.queryFiled = 'name';
                this.$Progress.start();
                this.fetchFolder();
                this.$Progress.finish();
            },
            create(){
                window.location.replace(`/dossiers/nouveau/`);
                //$('#folder-store').appendTo('body').modal('show');
            },
            destroy(folder){
                swal({
                    title: "Êtes-vous sûr?",
                    text: "Vous ne pourrez pas récupérer ce dossier après supression!",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }, () => {
                    axios.get('/dossiers/'+folder.id+'/supression').then(response => {
                        this.fetchFolders();
                        toastr['success']("Le dossier a bien été supprimé", '', {timeOut: 5000, closeButton: true});
                    }).catch(error => {
                        this.toast(error);
                    });
                });
            },
            goFiles(id){
                window.location.replace('/dossiers/'+id+'/documents');
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
                this.isEditing = false;
                this.errors = [];
                this.form.name = '';
                this.form.reference = '';
                this.form.description = '';
                this.fetchFolders();
                $('#folder-store').modal('hide');
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
