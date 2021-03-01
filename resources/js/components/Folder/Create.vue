<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Création d'un dossier</strong></h4>
        </div>
        <div class="bg-white mt-4 mb-4">
            <div class="row clearfix">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-primary btn-round btn-default float-right" @click.prevent="save" :disabled="loading">
                        <template v-if="!loading"><i class='fa fa-save'></i> ENREGISTRER</template>
                        <template v-if="loading"><i class='fa fa-spin fa-spinner'></i> EN COURS DE CREATION...</template>
                    </button>
                </div>
            </div>
            <div class="row clearfix mt-4">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Numéro du décret <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.number" class="form-control" placeholder="Numéro du décret" />
                        <span class="text-danger" v-if="getErrors.number">
                            {{ getErrors.number[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date du décret <span class="text-danger">*</span></label>
                                <input type="date" v-model="form.date_decret" class="form-control" placeholder="Date du décret" />
                                <span class="text-danger" v-if="getErrors.date_decret">
                                    {{ getErrors.date_decret[0] }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Date de décoration <span class="text-danger">*</span></label>
                                <input type="date" v-model="form.date_decoration" class="form-control" placeholder="Date de décoration" />
                                <span class="text-danger" v-if="getErrors.date_decoration">
                                    {{ getErrors.date_decoration[0] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-4">
                
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea v-model="form.description" class="form-control" placeholder="Description" ></textarea>
                        <span class="text-danger" v-if="getErrors.description">
                            {{ getErrors.description[0] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white mt-4 mb-4 row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30%">Type de décoration</th>
                            <th style="width: 25%">Nom de la stucture</th>
                            <th style="width: 42%">Personnes affectées par le décret</th>
                            <th style="width: 3%">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(member, index) in form.members">
                            <td><multiselect placeholder="Choisir un type de décoration" v-model="form.members[index].category_id"  track-by="id" label="name" :options="categories"></multiselect> </td>
                            <td><input type="text" class="form-control" v-model="form.members[index].structure"></td>
                            <td><textarea class="form-control" v-model="form.members[index].description"></textarea></td>
                            <td style="text-align: center; width: 50px;" class="align-middle">
                                <div class="form-material floating">
                                    <a href="#" style="text-align: center;" @click="removeLine(index)"><i style="color:#ff0000;" class="fa fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="button" class="btn btn-link" @click.stop="addLine" value="Ajouter un lot de personnes +">
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
                setting:{},
                form:{number:'',description:'',date_decret:'',date_decoration:'',members:[]},
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
                categories: [],
                members: [],
            }
        },
        mounted() {
            this.categories = this.categories_data;
        },
        methods: {
            save(){
                this.loading = true;
                this.errors = [];
                axios.post('/dossiers/nouveau',this.form).then(response => {
                    this.loading = false;
                    window.location.replace('/dossiers/'+response.data.folder_id+'/documents');
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            addLine() {
                this.form.members.push({category_id:'',structure:'',description:''});
            },
            removeLine(index) {
                this.form.members.splice(index, 1);
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
