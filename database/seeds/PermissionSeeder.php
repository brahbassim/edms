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
                'name' => 'Recherche par nom et prénom',
                'slug' => 'fullname-dashboard',
            ],
            [
                'name' => 'Recherche par n° de décret',
                'slug' => 'decret-dashboard',
            ],
            [
                'name' => 'Recherche par date de décret',
                'slug' => 'datedecret-dashboard',
            ],
            // Profile
            [
                'name' => 'Editer son profil',
                'slug' => 'edit-profile',
            ],
            [
                'name' => 'Mettre à jour son profil',
                'slug' => 'update-profile',
            ],
            // Users
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
            ],
            // Roles
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
            // Dashboard permission
            [
                'name' => 'Visualiser tableau de bord',
                'slug' => 'list-dashboard',
            ],
            // Folders
            [
                'name' => 'Lister les décrets',
                'slug' => 'index-folder',
            ],
            [
                'name' => 'Rechercher un décret',
                'slug' => 'search-folder',
            ],
            [
                'name' => 'Créer un décret',
                'slug' => 'create-folder',
            ],
            [
                'name' => 'Enrregistrer un décret',
                'slug' => 'store-folder',
            ],
            [
                'name' => 'Editer un décret',
                'slug' => 'edit-folder',
            ],
            [
                'name' => 'Mettre à jour un décret',
                'slug' => 'update-folder',
            ],
            [
                'name' => 'Supprimer un décret',
                'slug' => 'destroy-folder',
            ],
            // Documents
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
            ],
            [
                'name' => 'Supprimer un document',
                'slug' => 'destroy-document',
            ],
            // Decorations
            [
                'name' => 'Lister les types de décorations',
                'slug' => 'index-decoration',
            ],
            [
                'name' => 'Rechercher un type de décoration',
                'slug' => 'search-decoration',
            ],
            [
                'name' => 'Créer untype de décoration',
                'slug' => 'create-decoration',
            ],
            [
                'name' => 'Enrregistrer un type de décoration',
                'slug' => 'store-decoration',
            ],
            [
                'name' => 'Editer untype de décoration',
                'slug' => 'edit-decoration',
            ],
            [
                'name' => 'Mettre à jour un type de décoration',
                'slug' => 'update-decoration',
            ],
            [
                'name' => 'Supprimer un type de décoration',
                'slug' => 'destroy-decoration',
            ],
            //Grades
            [
                'name' => 'Lister les grades',
                'slug' => 'index-grade',
            ],
            [
                'name' => 'Créer un grade',
                'slug' => 'create-grade',
            ],
            [
                'name' => 'Enrregistrer un grade',
                'slug' => 'store-grade',
            ],
            [
                'name' => 'Editer un grade',
                'slug' => 'edit-grade',
            ],
            [
                'name' => 'Mettre à jour un grade',
                'slug' => 'update-grade',
            ],
            [
                'name' => 'Supprimer un grade',
                'slug' => 'destroy-grade',
            ],
            // Stat
            [
                'name' => 'Lister les statistiques',
                'slug' => 'index-stat',
            ],
        ]);
    }
}
