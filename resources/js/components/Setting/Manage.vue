<template>
    <div class="container-fluid">
        <div class="header">
            <h4><strong>Gestion des paramètres</strong></h4>
        </div>
        <div class="bg-white mt-4 mb-4">
            <div class="row clearfix">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-success btn-round btn-default float-right" @click.prevent="save" :disabled="loading">
                        <template v-if="!loading">METTRE À JOUR</template>
                        <template v-if="loading"><i class='fa fa-spin fa-spinner'></i> EN COURS DE MISE À JOUR...</template>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Nom de l'entreprise <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.name" class="form-control" placeholder="Nom de l'entreprise" />
                        <span class="text-danger" v-if="getErrors.name">
                            {{ getErrors.name[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.email" class="form-control" placeholder="Email de l'entreprise" />
                        <span class="text-danger" v-if="getErrors.email">
                            {{ getErrors.email[0] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="row clearfix mt-4">
                <div class="col-sm-2 text-center">
                    <div class="form-group">
                        <div class="upload-btn-wrapper">
                            <button class="btn-upload"><i class="fa fa-image">  Logo</i></button>
                            <input type="file" ref="logo" id="file" accept="image/*" @change="handleLogoUpload"><br>
                            <span class="text-danger" v-if="getErrors.logo">
                                {{ getErrors.logo[0] }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 text-center mt-3">
                    <label>Actuel</label>
                    <img height="80" :src="form.logo" alt="" style="border:dashed 2px gray;padding:5px;">
                </div>
                <div class="col-sm-2 text-center mt-3">
                    <label>Nouveau</label>
                    <img height="80" :src="logoPreview" style="border:dashed 2px gray;padding:5px;"/>
                </div>

                <div class="col-sm-2 text-center">
                    <div class="form-group">
                        <div class="upload-btn-wrapper">
                            <button class="btn-upload"><i class="fa fa-image">  Signature</i></button>
                            <input type="file" ref="sign" id="" accept="image/*" @change="handleSignUpload"><br>
                            <span class="text-danger" v-if="getErrors.sign">
                                {{ getErrors.sign[0] }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 text-center mt-3">
                    <label>Actuel</label>
                    <img height="80" :src="form.sign" alt="" style="border:dashed 2px gray;padding:5px;">
                </div>
                <div class="col-sm-2 text-center mt-3">
                    <label>Nouveau</label>
                    <img height="80" :src="signPreview" style="border:dashed 2px gray;padding:5px;"/>
                </div>

            </div>
            <div class="row clearfix">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Téléphone <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.phone" class="form-control" placeholder="Numéro de téléphone" />
                        <span class="text-danger" v-if="getErrors.phone">
                            {{ getErrors.phone[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Pays <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.country" class="form-control" placeholder="Entrer le pays" />
                        <span class="text-danger" v-if="getErrors.country">
                            {{ getErrors.country[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Ville <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.city" class="form-control" placeholder="Entrer la ville" />
                        <span class="text-danger" v-if="getErrors.city">
                            {{ getErrors.city[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Boite postal </label>
                        <input type="text" v-model="form.zip" class="form-control" placeholder="Entrer la boite postal" />
                        <span class="text-danger" v-if="getErrors.zip">
                            {{ getErrors.zip[0] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Code postal <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.postal" class="form-control" placeholder="Numéro le code postal" />
                        <span class="text-danger" v-if="getErrors.postal">
                            {{ getErrors.postal[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Adresse 1 <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.address_one" class="form-control" placeholder="Entrer l'adresse 1" />
                        <span class="text-danger" v-if="getErrors.address_one">
                            {{ getErrors.address_one[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Adresse 2</label>
                        <input type="text" v-model="form.address_two" class="form-control" placeholder="Entrer l'adresse 2" />
                        <span class="text-danger" v-if="getErrors.address_two">
                            {{ getErrors.address_two[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>NIF <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.tin" class="form-control" placeholder="Entrer le NIF" />
                        <span class="text-danger" v-if="getErrors.tin">
                            {{ getErrors.tin[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Code couleur <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.color" class="form-control" placeholder="Entrer le code couleur" />
                        <span class="text-danger" v-if="getErrors.color">
                            {{ getErrors.color[0] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Site Web </label>
                        <input type="text" v-model="form.website" class="form-control" placeholder="Le site web" />
                        <span class="text-danger" v-if="getErrors.website">
                            {{ getErrors.website[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Préfix des factures <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.invoice_prefix" class="form-control" placeholder="Entrer le préfix des factures" />
                        <span class="text-danger" v-if="getErrors.invoice_prefix">
                            {{ getErrors.invoice_prefix[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Préfix des factures<span class="text-danger">*</span></label>
                        <input type="text" v-model="form.estimate_prefix" class="form-control" placeholder="Entrer le préfix des devis" />
                        <span class="text-danger" v-if="getErrors.estimate_prefix">
                            {{ getErrors.estimate_prefix[0] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>RCCM<span class="text-danger">*</span></label>
                        <input type="text" v-model="form.rccm" class="form-control" placeholder="Entrer le RCCM" />
                        <span class="text-danger" v-if="getErrors.rccm">
                            {{ getErrors.rccm[0] }}
                        </span>
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
                setting:{},
                form:{name: '',email:'',phone: '',country: '',city:'',zip:'',postal:'',address_one:'',address_two:'',tin:'',rccm:'',website:'',invoice_prefix:'',estimate_prefix:''},
                logo: '',
                sign:'',
                showPreview: false,
                logoPreview: '',
                signPreview: '',
                pagination:{current_page: 1},
                loading: false,
                isEditing:false,
            }
        },
        mounted() {
            this.fetchSetting();
        },
        methods: {
            fetchSetting(){
                axios.get('/parametres/').then(response => {
                    this.form = response.data;
                }).catch(error => {
                    this.toast(error);
                })
            },
            save(){
                this.loading = true;
                this.errors = [];
                let formData = new FormData();
                if(this.$refs.logo){
                    formData.append('logo', this.$refs.logo.files[0] ? this.$refs.logo.files[0] : '');
                }
                if(this.$refs.sign){
                    formData.append('sign', this.$refs.sign.files[0] ? this.$refs.sign.files[0] : '');
                }
                formData.append('name', this.form.name);
                formData.append('email', this.form.email);
                formData.append('phone', this.form.phone);
                formData.append('color', this.form.color);
                formData.append('country', this.form.country);
                formData.append('city', this.form.city);
                formData.append('zip', this.form.zip);
                formData.append('postal', this.form.postal);
                formData.append('address_one', this.form.address_one);
                formData.append('address_two', this.form.address_two);
                formData.append('tin', this.form.tin);
                formData.append('website', this.form.website);
                formData.append('rccm', this.form.rccm);
                formData.append('invoice_prefix', this.form.invoice_prefix);
                formData.append('estimate_prefix', this.form.estimate_prefix);
                axios.post('/parametres/'+this.form.id+'/edition', formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    toastr['success']("Les paramètres ont bien été mis à jour" , '', {timeOut: 5000, closeButton: true});
                    this.loading = false;
                    this.fetchSetting();
                }).catch(error => {
                    this.loading = false;
                    this.toast(error);
                });
            },
            handleLogoUpload(){
                this.logo = this.$refs.logo.files[0];
                let reader  = new FileReader();
                reader.addEventListener("load", function () {
                    //this.showPreview = true;
                    this.logoPreview = reader.result;
                }.bind(this), false);
                if(this.logo){
                    if ( /\.(jpe?g|png|gif)$/i.test( this.logo.name ) ) {
                        reader.readAsDataURL( this.logo );
                    }
                }
            },
            handleSignUpload(){
                this.sign = this.$refs.sign.files[0];
                let reader  = new FileReader();
                reader.addEventListener("load", function () {
                    this.signPreview = reader.result;
                }.bind(this), false);
                if(this.sign){
                    if ( /\.(jpe?g|png|gif)$/i.test( this.sign.name ) ) {
                        reader.readAsDataURL( this.sign );
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
