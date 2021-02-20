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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Dossiers</div>
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
            <div class="col-sm-6">
                <div class="table-responsive card">
                    <a href="/dossiers" class="btn btn-primary mb-4">Gérer les dossiers</a>
                    <a href="/utilisateurs" class="btn btn-success mb-4">Gérer les utilisateurs</a>
                    <a href="/profil" class="btn btn-warning ">Gérer mon compte</a>
                </div>
            </div>
            <div class="col-sm-6">
                
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
            }
        },
        mounted() {
        },
        methods: {

        },
        beforeDestroy: function(){
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
