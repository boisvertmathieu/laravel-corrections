<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competence;

class CompetencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $competence = new Competence();
        $competence->nom = "Gestion et versionnage du projet";
        $competence->description = "Respect des consignes de versionnage / Respect des consigne de gestion avec Trello / Assiduité dans l'utilisation des outils de versionnage et de gestion";
        $competence->ponderation = 20;
        $competence->grille_id = 1;
        $competence->save();

        $competence = new Competence();
        $competence->nom = "Tests";
        $competence->description = "Application adéquate d’un scénario de tests (HTTP et fonctionnel) / Application des contrôles de qualité demandés / Bonne validation des résultats";
        $competence->ponderation = 20;
        $competence->grille_id = 1;
        $competence->save();

        $competence = new Competence();
        $competence->nom = "Qualité du code";
        $competence->description = "Utilisation adéquate de Laravel / Utilisation adéquate de Bootstrap avec Sass";
        $competence->ponderation = 20;
        $competence->grille_id = 1;
        $competence->save();

        $competence = new Competence();
        $competence->nom = "Évolution de l'application";
        $competence->description = "Ajout fonctionnel des fonctionnalités à développer (Backlog)";
        $competence->ponderation = 40;
        $competence->grille_id = 1;
        $competence->save();
    }
}
