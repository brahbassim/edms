<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Gestion des fournisseurs</strong></h4>
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
                        <input v-model="query" @keyup.enter="searchProviders" type="text" class="form-control" placeholder="Rechercher...">
                        <div class="input-group-append">
                            <span class="input-group-text" @click.prevent="searchProviders"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bg-white mt-4 mb-4">
            <div class="col-sm-12">
                <button type="button" class="btn  btn-primary mb-4 float-right" @click="create">Ajouter un fournisseur <i class="fa fa-th-list"></i></button>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>LOGO</th>
                            <th>NOM</th>
                            <th>EMAIL</th>
                            <th>TÉLÉPHONE</th>
                            <th>ADRESSE</th>
                            <th>INFORMATIONS</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center" v-show="providers.length" v-for="(provider, index) in providers" :key="provider.id">
                            <td class="align-middle"><img width="60" :src="provider.logo" alt=""></td>
                            <td class="align-middle">{{ provider.name}}</td>
                            <td class="align-middle">{{provider.email}}</td>
                            <td class="align-middle">{{provider.phone}}</td>
                            <td class="align-middle">{{provider.address}}</td>
                            <td class="align-middle">
                                <p><strong>TYPE</strong> :{{provider.type}}</p>
                                <p><strong>GERANT</strong> :{{provider.manager}}</p>
                                <p><strong>SITE WEB</strong> :{{provider.website}}</p>
                            </td>
                            <td class="align-middle">
                                <button @click.prevent="edit(provider)" class="btn btn-warning btn-sm" style="width:30px;"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" style="margin-top: 5px;" id="provider-store" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header mb-4">
                        <h4 v-if="!isEditing" class="title largeModalLabel">Création d'un fournisseur</h4>
                        <h4 v-if="isEditing" class="title largeModalLabel">Modifier un fournisseur</h4>
                        <button type="button" class="close" @click.prevent="resetForm" aria-label="Fermer" :disabled="loading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Type <span class="text-danger">*</span></label>
                                    <select v-model="form.type" ref="select" class="form-control">
                                        <option v-for="t in typeOptions" :value="t.type">{{t.value}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nom du gérant <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.manager" class="form-control" placeholder="Entrer le nom du manager" />
                                    <span class="text-danger" v-if="getErrors.manager">
                                        {{ getErrors.manager[0] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Nom <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.name" class="form-control" placeholder="Entrer le nom du fournisseur" />
                                    <span class="text-danger" v-if="getErrors.name">
                                        {{ getErrors.name[0] }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" v-model="form.email" class="form-control" placeholder="Entrer l'email" />
                                    <span class="text-danger" v-if="getErrors.email">
                                        {{ getErrors.email[0] }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" v-model="form.phone" class="form-control" placeholder="Entrer le téléphone" />
                                    <span class="text-danger" v-if="getErrors.phone">
                                        {{ getErrors.phone[0] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix mt-4">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label>Site Web</label>
                                    <input type="text" v-model="form.website" class="form-control" placeholder="Site Web" />
                                    <span class="text-danger" v-if="getErrors.website">
                                        {{ getErrors.website[0] }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-2 ">
                                <div class="form-group">
                                    <div class="upload-btn-wrapper">
                                        <label>Logo</label>
                                        <button class="btn-upload"><i class="fa fa-image">  Logo</i></button>
                                        <input type="file" ref="logo" id="file" accept="image/*" @change="handleLogoUpload"><br>
                                        <span class="text-danger" v-if="getErrors.logo">
                                            {{ getErrors.logo[0] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-if="!isEditing" class="col-sm-2 text-center mt-3">
                                <img height="60" :src="logoPreview" v-show="showPreview" style="border:dashed 2px gray;padding:5px;"/>
                            </div>
                            <template v-if="isEditing">
                                <div class="col-sm-2">
                                    <label>Actuel</label>
                                    <img height="100" :src="form.logo" alt="">
                                </div>
                                <div class="col-sm-2">
                                    <label v-show="showPreview">Nouveau</label>
                                    <img height="60" :src="logoPreview" v-show="showPreview" style="border:dashed 2px gray;padding:5px;"/>
                                </div>
                            </template>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12 text-center">
                                <label class="float-left">Adresse <span class="text-danger">*</span></label>
                                <textarea type="text" v-model="form.address" class="form-control" placeholder="Adresse" ></textarea>
                                <span class="text-danger" v-if="getErrors.website">
                                        {{ getErrors.website[0] }}
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
        data () {
            return {
                errors: [],
                typeOptions: [{type: 'PARTICULIER', value: 'Particulier'},{type: 'ENTREPRISE' ,value: 'Entreprise'}],
                queryOptions: [{field: 'name', value: 'Nom'},{field: 'email' ,value: 'Email'},{field: 'phone', value: 'Téléphone'}],
                queryFiled: 'name',
                query:'',
                providers: [],
                form:{name: '',email: '',phone: '',website:'',address:'',type:'PARTICULIER',manager:''},
                logo: '',
                showPreview: false,
                logoPreview: '',
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
            }
        },
        mounted() {
            this.fetchProviders();
        },
        methods: {
            fetchProviders(){
                axios.get('/fournisseurs/?page='+this.pagination.current_page).then(response => {
                    this.providers = response.data.data;
                }).catch(error => {
                    this.toast(error);
                })
            },
            searchProviders(){
                if (this.query === ''){
                    toastr['warning']('Le champ recherche est requit!', 'Oops', {timeOut: 5000, closeButton: true});
                } else {
                    this.$Progress.start();
                    axios.get('/providers/search/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page).then(response => {
                        this.providers = response.data.data;
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
                    url = '/fournisseurs/nouveau';
                }else {
                    url = '/fournisseurs/'+this.form.id+'/edition';
                }
                this.loading = true;
                this.errors = [];
                let formData = new FormData();
                if(this.$refs.logo){
                    formData.append('logo', this.$refs.logo.files[0] ? this.$refs.logo.files[0] : '');
                }
                formData.append('name', this.form.name);
                formData.append('email', this.form.email);
                formData.append('phone', this.form.phone);
                formData.append('address', this.form.address);
                formData.append('website', this.form.website);
                formData.append('type', this.form.type);
                formData.append('manager', this.form.manager);
                console.log(this.form);
                axios.post(url, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.resetForm();
                    toastr['success'](this.isEditing ? "Le fournisseur a bien été créé" : "Le fournisseur a bien été mis à jour", '', {timeOut: 5000, closeButton: true});
                    this.loading = false;
                    this.isEditing = false;
                    this.fetchProviders();
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            edit(provider){
                this.isEditing = true;
                this.form = provider;
                $('#provider-store').appendTo('body').modal('show');
            },
            reload() {
                this.query = '';
                this.queryFiled = 'name';
                this.$Progress.start();
                this.fetchProviders();
                this.$Progress.finish();
            },
            create(){
                $('#provider-store').appendTo('body').modal('show');
            },
            destroy(provider){
                swal({
                    title: "Êtes-vous sûr?",
                    text: "Vous ne pourrez pas récupérer ce fournisseur après supression!",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }, () => {
                    axios.get('/fournisseurs/'+provider.id+'/supression').then(response => {
                        this.fetchProviders();
                        toastr['success']("Le fournisseur a bien été supprimé", '', {timeOut: 5000, closeButton: true});
                    }).catch(error => {
                        this.toast(error);
                    });
                });
            },
            handleLogoUpload(){
                this.logo = this.$refs.logo.files[0];
                let reader  = new FileReader();
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.logoPreview = reader.result;
                }.bind(this), false);
                if(this.logo){
                    if ( /\.(jpe?g|png|gif)$/i.test( this.logo.name ) ) {
                        reader.readAsDataURL( this.logo );
                    }
                }
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
                this.$refs.logo = null;
                this.form.name = '';
                this.form.email = '';
                this.form.phone = '';
                this.website = '';
                this.address = '';
                this.manager = '';
                this.showPreview = false;
                this.logoPreview = '';
                this.fetchProviders();
                $('#provider-store').modal('hide');
            },
            goProfile(){
                window.location.replace(`/profils`);
            },
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
