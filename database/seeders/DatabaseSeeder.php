<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Grille;
use App\Models\Competence;
use App\Models\Critere;
use App\Models\Correction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Création des données principales de test pour l'utilisateur "admin"
        $this->call(UsersTableSeeder::class);
        $this->call(GrillesTableSeeder::class);
        $this->call(CompetencesTableSeeder::class);
        $this->call(CriteresTableSeeder::class);
        $this->call(CorrectionsTableSeeder::class);
        $this->call(NoteTableSeeder::class);


        // Création aléatoire de données (utilisateurs, grilles, etc.)
        User::factory()->times(3)->create();
        Grille::factory()->times(40)->create();
        Competence::factory()->times(120)->create();
        Critere::factory()->times(180)->create();
        Correction::factory()->times(30)->create();
    }
}
