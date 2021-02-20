<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        DB::table('permissions')->insert([
            [
                'name' => 'Editer son profil',
                'slug' => 'edit-profile',
            ],
            [
                'name' => 'Mettre à jour son profil',
                'slug' => 'update-profile',
            ],// Profile permissions ---------------------
            [
                'name' => 'Lister les users',
                'slug' => 'index-user',
            ],
            [
                'name' => 'Rechercher un user',
                'slug' => 'search-user',
            ],
            [
                'name' => 'Créer un user',
                'slug' => 'create-user',
            ],
            [
                'name' => 'Enrregistrer un user',
                'slug' => 'store-user',
            ],
            [
                'name' => 'Editer un user',
                'slug' => 'edit-user',
            ],
            [
                'name' => 'Changer le statut d\'un user',
                'slug' => 'status-user',
            ],
            [
                'name' => 'Mettre à jour un user',
                'slug' => 'update-user',
            ],
            [
                'name' => 'Supprimer un user',
                'slug' => 'destroy-user',
            ],// Profiles
            [
                'name' => 'Lister les roles',
                'slug' => 'index-role',
            ],
            [
                'name' => 'Rechercher un role',
                'slug' => 'search-role',
            ],
            [
                'name' => 'Créer un role',
                'slug' => 'create-role',
            ],
            [
                'name' => 'Enrregistrer un role',
                'slug' => 'store-role',
            ],
            [
                'name' => 'Editer un role',
                'slug' => 'edit-role',
            ],
            [
                'name' => 'Changer le statut d\'un role',
                'slug' => 'status-role',
            ],
            [
                'name' => 'Mettre à jour un role',
                'slug' => 'update-role',
            ],
            [
                'name' => 'Supprimer un role',
                'slug' => 'destroy-role',
            ],
            [
                'name' => 'Visualiser tableau de bord',
                'slug' => 'list-dashboard',
            ],// Dashboard permission ---------------------
            [
                'name' => 'Lister les dossiers',
                'slug' => 'index-folder',
            ],
            [
                'name' => 'Rechercher un dossier',
                'slug' => 'search-folder',
            ],
            [
                'name' => 'Créer un dossier',
                'slug' => 'create-folder',
            ],
            [
                'name' => 'Enrregistrer un dossier',
                'slug' => 'store-folder',
            ],
            [
                'name' => 'Editer un dossier',
                'slug' => 'edit-folder',
            ],
            [
                'name' => 'Mettre à jour un dossier',
                'slug' => 'update-folder',
            ],
                // Folders
            [
                'name' => 'Lister les documents',
                'slug' => 'index-document',
            ],
            [
                'name' => 'Rechercher un document',
                'slug' => 'search-document',
            ],
            [
                'name' => 'Créer un document',
                'slug' => 'create-document',
            ],
            [
                'name' => 'Enrregistrer un document',
                'slug' => 'store-document',
            ],
            [
                'name' => 'Editer un document',
                'slug' => 'edit-document',
            ],
            [
                'name' => 'Mettre à jour un document',
                'slug' => 'update-document',
            ]
                // Documents
        ]);
    }
}
