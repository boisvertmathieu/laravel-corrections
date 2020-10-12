<?php

namespace Database\Seeders;

use App\Models\Correction;
use Illuminate\Database\Seeder;

class CorrectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $correction = new Correction();
        $correction->grille_id = 1;
        $correction->nom = "John";
        $correction->prenom = "Papa";
        $correction->da = 1000000;
        $correction->save();
    }
}
