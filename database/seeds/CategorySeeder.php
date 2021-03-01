<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        DB::table('categories')->insert([
            [
                'name' => 'Ordre National du Niger',
                'description' => ''
            ],
            [
                'name' => 'Ordre du Mérite du Niger',
                'description' => ''
            ],
            [
                'name' => 'Médaille Militaire',
                'description' => ''
            ],
            [
                'name' => 'Croix de la Vigilance',
                'description' => ''
            ],
            [
                'name' => "Médaille d'Honneur de la Santé Publique",
                'description' => ''
            ],
            [
                'name' => 'Ordre du Mérite Agricole',
                'description' => ''
            ]
        ]);
    }
}
