<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grille;

class GrillesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grille = new Grille();
        $grille->user_id = 1;
        $grille->nom = "TP2 - Mise à jour d'une application web";
        $grille->cours = "Évolution et qualité d'une application informatique";
        $grille->save();

    }
}
