<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Gestion des grades</strong></h4>
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
                        <input v-model="query" @keyup.enter="searchGrades" type="text" class="form-control" placeholder="Rechercher...">
                        <div class="input-group-append">
                            <span class="input-group-text" @click.prevent="searchGrades"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row bg-white mt-4 mb-4">
            <div class="col-sm-12">
                <button type="button" class="btn  btn-primary mb-4 float-right" @click="create">Ajouter un grade <i class="fa fa-user"></i></button>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>NOM</th>
                            <th>DESCRIPTION</th>
                            <th>DECORATION</th>
                            <th>ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center" v-show="grades.length" v-for="(grade, index) in grades" :key="grade.id">
                            <td class="align-middle">{{ grade.name}}</td>
                            <td class="align-middle">{{ grade.description}}</td>
                            <td class="align-middle">{{ grade.category.name}}</td>
                            <td class="align-middle">
                                <button v-show="$can('edit-grade')" @click.prevent="edit(grade)" class="btn btn-warning btn-sm" style="width:30px;"><i class="fa fa-edit"></i></button>
                                <button v-show="$can('destroy-grade')" @click.prevent="destroy(grade)" class="btn btn-danger btn-sm" style="width:30px;"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" style="margin-top: 5px;" id="grade-store" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header mb-4">
                        <h4 v-if="!isEditing" class="title largeModalLabel">Création d'un grade</h4>
                        <h4 v-if="isEditing" class="title largeModalLabel">Modifier un grade</h4>
                        <button type="button" class="close" @click.prevent="resetForm" aria-label="Fermer" :disabled="loading">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Décoration <span class="text-danger">*</span></label>
                                    <multiselect placeholder="Choisir un type de décoration" v-model="form.category_id"  track-by="id" label="name" :options="categories"></multiselect>
                                </div>
                            </div>
                        </div>
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
         props:['categories_data'],
        data () {
            return {
                errors: [],
                queryOptions: [{field: 'name', value: 'Nom'},{field: 'description', value: 'Description'}],
                queryFiled: 'name',
                query:'',
                grades: [],
                form:{name: '',description:'',category_id:''},
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
                categories: [],
            }
        },
        mounted() {
            this.fetchGrades();
            this.categories = this.categories_data;
        },
        methods: {
            fetchGrades(){
                axios.get('/grades/?page='+this.pagination.current_page).then(response => {
                    this.grades = response.data.data;
                }).catch(error => {
                    this.toast(error);
                })
            },
            searchGrades(){
                if (this.query === ''){
                    toastr['warning']('Le champ recherche est requit!', 'Oops', {timeOut: 5000, closeButton: true});
                } else {
                    this.$Progress.start();
                    axios.get('/grades/search/'+this.queryFiled+'/'+this.query+'?page='+this.pagination.current_page).then(response => {
                        this.grades = response.data.data;
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
                    url = '/grades/nouveau';
                }else {
                    url = '/grades/'+this.form.id+'/edition';
                }
                this.loading = true;
                this.errors = [];
                let formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('description', this.form.description);
                formData.append('category_id', this.form.category_id.id);
                axios.post(url, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.resetForm();
                    toastr['success'](this.isEditing ? "Le grade a bien été créé" : "Le grade a bien été mis à jour", '', {timeOut: 5000, closeButton: true});
                    this.loading = false;
                    this.isEditing = false;
                    this.fetchGrades();
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            edit(grade){
                this.isEditing = true;
                this.form = grade;
                this.form.category_id = grade.category;
                $('#grade-store').appendTo('body').modal('show');
            },
            reload() {
                this.query = '';
                this.queryFiled = 'name';
                this.$Progress.start();
                this.fetchGrades();
                this.$Progress.finish();
            },
            create(){
                $('#grade-store').appendTo('body').modal('show');
            },
            destroy(grade){
                swal({
                    title: "Êtes-vous sûr?",
                    text: "Vous ne pourrez pas récupérer ce grade après supression!",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }, () => {
                    axios.get('/grades/'+grade.id+'/supression').then(response => {
                        this.fetchGrades();
                        toastr['success']("Le grade a bien été supprimé", '', {timeOut: 5000, closeButton: true});
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
                
                this.fetchGrades();
                $('#grade-store').modal('hide');
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
