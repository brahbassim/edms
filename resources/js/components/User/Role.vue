<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Gestion des roles</strong></h4>
        </div>
        <div class="row bg-white mt-4 mb-4">
            <div class="col-sm-12">
                <button type="button" class="btn  btn-secondary mb-4 float-left" @click="back">Retourner aux utilisateurs <i class="fa fa-reply"></i></button>
                <button type="button" class="btn  btn-primary mb-4 float-right" @click="create">Ajouter un role <i class="fa fa-user"></i></button>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>NOM</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center" v-show="roles.length" v-for="(role, index) in roles" :key="role.id">
                            <td class="align-middle">{{ role.name}}</td>
                            <td class="align-middle">
                                <button v-show="$can('edit-role')" @click.prevent="edit(role)" class="btn btn-warning btn-sm" style="width:30px;"><i class="fa fa-edit"></i></button>
                                <button v-show="$can('destroy-role')" @click.prevent="destroy(role)" class="btn btn-danger btn-sm" style="width:30px;"><i class="fa fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" style="margin-top: 5px;" id="role-store" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header mb-4">
                        <h4 v-if="!isEditing" class="title largeModalLabel">Création d'un role</h4>
                        <h4 v-if="isEditing" class="title largeModalLabel">Modifier un role</h4>
                        <button type="button" class="close" @click.prevent="resetForm" aria-label="Fermer" :disabled="loading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Nom </label>
                                    <input type="text" v-model="form.name" class="form-control" placeholder="Entrer le nom" />
                                    <span class="text-danger" v-if="getErrors.name">
                                        {{ getErrors.name[0] }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-8 text-center">
                                <label> </label>
                                <button class="btn btn-primary mt-4" type="button" data-toggle="collapse" data-target="#collapsePermissions" aria-expanded="false" aria-controls="collapsePermissions">
                                    Attribuer des permissions
                                </button>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="collapse" id="collapsePermissions" style="max-height:300px; margin-top-20px;overflow-y: scroll">
                                    <div class="card card-body" style="">
                                        <label v-for="(p, key) in permissions" style="margin-left: 15px;">
                                            {{ p.name }}
                                            <input type="checkbox" class="checkbox" v-model="form.permissions" :key="key" :value="p.id">
                                        </label>
                                    </div>
                                </div>
                                <span class="text-danger" v-if="getErrors.permissions">
                                    {{ getErrors.permissions[0] }}
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
        props:['user_permissions'],
        data () {
            return {
                errors: [],
                permissions: [],
                roles: [],
                form:{name: '',permissions: []},
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
            }
        },
        mounted() {
            this.fetchRoles();
            this.permissions = this.user_permissions;
        },
        methods: {
            fetchRoles(){
                axios.get('/roles/?page='+this.pagination.current_page).then(response => {
                    this.roles = response.data.data;
                    this.pagination = response.data.meta;
                }).catch(error => {
                    this.toast(error);
                })
            },
            save(){
                let url = '';
                if(!this.isEditing){
                    url = '/roles/nouveau';
                }else {
                    url = '/roles/'+this.form.id+'/edition';
                }
                this.loading = true;
                this.errors = [];
                let formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('permissions', JSON.stringify(this.form.permissions));
                axios.post(url, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.resetForm();
                    toastr['success'](this.isEditing ? "Le role a bien été créé" : "Le role a bien été mis à jour", '', {timeOut: 5000, closeButton: true});
                    this.loading = false;
                    this.isEditing = false;
                    this.fetchRoles();
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            edit(role){
                this.isEditing = true;
                this.form = role;
                $('#role-store').appendTo('body').modal('show');
            },
            reload() {
                this.$Progress.start();
                this.fetchRoles();
                this.$Progress.finish();
            },
            create(){
                $('#role-store').appendTo('body').modal('show');
            },
            destroy(role){
                swal({
                    title: "Êtes-vous sûr?",
                    text: "Vous ne pourrez pas récupérer cet role après supression!",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }, () => {
                    axios.get('/roles/'+role.id+'/supression').then(response => {
                        this.fetchRoles();
                        toastr['success']("Le role a bien été supprimé", '', {timeOut: 5000, closeButton: true});
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
                this.isEditing = false;
                this.errors = [];
                this.form.name = '';
                this.form.permissions = [];
                this.fetchRoles();
                $('#role-store').modal('hide');
            },
            updatePermissions () {
                //this.$emit('input', this.form.permissions);
            },
            back(){
                window.location.replace(`/utilisateurs`);
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
