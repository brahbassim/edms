<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Gestion des documents</strong></h4>
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
                        <input v-model="query" @keyup.enter="searchDocuments" type="text" class="form-control" placeholder="Rechercher...">
                        <div class="input-group-append">
                            <span class="input-group-text" @click.prevent="searchDocuments"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bg-white mt-4 mb-4">
            <div class="col-sm-12">
                <button type="button" class="btn  btn-primary mb-4 float-right" @click="create">Ajouter un document <i class="fa fa-file"></i></button>
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" id="" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>NOM</th>
                            <th>DESCRIPTION</th>
                            <th>TAILLE</th>
                            <th>EXTENSION</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>NOM</th>
                            <th>DESCRIPTION</th>
                            <th>TAILLE</th>
                            <th>EXTENSION</th>
                            <th>ACTIONS</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <tr class="text-center" v-show="documents.length" v-for="(document, index) in documents" :key="document.id">
                            <td class="align-middle">{{ document.name}}</td>
                            <td class="align-middle">{{document.description}}</td>
                            <td class="align-middle">{{document.filesize}}</td>
                            <td class="align-middle">{{document.mimetype}}</td>
                            <td class="align-middle">
                                <button @click.prevent="edit(document)" class="btn btn-warning btn-sm" style="width:30px;"><i class="fa fa-edit"></i></button>
                                <a :href="document.file" class="btn btn-primary btn-sm" download=""><i class="fa fa-download"></i> Télécharger</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <pagination v-if="pagination.last_page >= 1"
                                :pagination="pagination"
                                :offset="5"
                                @paginate="query === '' ? fetchDocuments() : searchDocuments()"
                    ></pagination>
                </div>
            </div>
        </div>
        <div class="modal fade" style="margin-top: 5px;" id="document-store" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header mb-4">
                        <h4 v-if="!isEditing" class="title largeModalLabel">Création d'un document</h4>
                        <h4 v-if="isEditing" class="title largeModalLabel">Modifier un document</h4>
                        <button type="button" class="close" @click.prevent="resetForm" aria-label="Fermer" :disabled="loading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nom <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.name" class="form-control" placeholder="Entrer le nom du document" />
                                    <span class="text-danger" v-if="getErrors.name">
                                        {{ getErrors.name[0] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix mt-4">
                            <div class="col-sm-2 ">
                                <div class="form-group">
                                    <div class="upload-btn-wrapper">
                                        <label>Uploader <span class="text-danger">*</span></label>
                                        <button class="btn-upload"><i class="fa fa-file">  Document <span class="text-danger">*</span></i></button>
                                        <input type="file" ref="doc" id="file"><br>
                                        <span class="text-danger" v-if="getErrors.doc">
                                            {{ getErrors.doc[0] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12 text-center">
                                <label class="float-left">Description <span class="text-danger">*</span></label>
                                <textarea type="text" v-model="form.description" class="form-control" placeholder="Description du document" />
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
    export default {
        props: ['id'],
        data () {
            return {
                errors: [],
                queryOptions: [{field: 'name', value: 'Nom'},{field: 'description' ,value: 'Description'}],
                queryFiled: 'name',
                query:'',
                documents: [],
                form:{name: '',description: ''},
                document: '',
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
            }
        },
        mounted() {
            this.fetchDocuments();
        },
        methods: {
            fetchDocuments(){
                axios.get('/dossiers/'+this.id+'/documents/?page='+this.pagination.current_page).then(response => {
                    this.documents = response.data.data;
                }).catch(error => {
                    this.toast(error);
                })
            },
            searchDocuments(){
                if (this.query === ''){
                    toastr['warning']('Le champ recherche est requit!', 'Oops', {timeOut: 5000, closeButton: true});
                } else {
                    this.$Progress.start();
                    axios.get('/dossiers/'+this.id+'/documents/search/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page).then(response => {
                        this.documents = response.data.data;
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
                    url = '/dossiers/'+this.id+'/documents/nouveau';
                }else {
                    url = '/dossiers/'+this.id+'/documents/'+this.form.id+'/edition';
                }
                this.loading = true;
                this.errors = [];
                let formData = new FormData();
                if(this.$refs.doc){
                    formData.append('doc', this.$refs.doc.files[0] ? this.$refs.doc.files[0] : '');
                }
                formData.append('name', this.form.name);
                formData.append('description', this.form.description);
                console.log(this.form);
                axios.post(url, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.resetForm();
                    toastr['success'](this.isEditing ? "Le document a bien été ajouté" : "Le document a bien été mis à jour", '', {timeOut: 5000, closeButton: true});
                    this.loading = false;
                    this.isEditing = false;
                    this.fetchDocuments();
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            edit(document){
                this.isEditing = true;
                this.form = document;
                $('#document-store').appendTo('body').modal('show');
            },
            reload() {
                this.query = '';
                this.queryFiled = 'name';
                this.$Progress.start();
                this.fetchDocuments();
                this.$Progress.finish();
            },
            create(){
                $('#document-store').appendTo('body').modal('show');
            },
            destroy(document){
                swal({
                    title: "Êtes-vous sûr?",
                    text: "Vous ne pourrez pas récupérer ce document après supression!",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }, () => {
                    axios.get('/dossiers/'+this.id+'/documents/'+document.id+'/supression').then(response => {
                        this.fetchDocuments();
                        toastr['success']("Le document a bien été supprimé", '', {timeOut: 5000, closeButton: true});
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
            download(){
                axios.get('this.link', { responseType: 'blob' })
                    .then(response => {
                        const blob = new Blob([response.data], { type: 'application/pdf' })
                        const link = document.createElement('a')
                        link.href = URL.createObjectURL(blob)
                        link.click()
                        URL.revokeObjectURL(link.href)
                    }).catch()
            },
            resetForm(){
                this.isEditing = false;
                this.errors = [];
                //this.$refs.doc = null;
                this.form.name = '';
                this.form.description = '';
                this.fetchDocuments();
                $('#document-store').modal('hide');
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
