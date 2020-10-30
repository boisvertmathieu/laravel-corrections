<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class Test extends DuskTestCase
{

    public function test()
    {
        // Création d'un utilisateur test avec Factory
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {

            // 1 - Connexion
            $browser->visit('/login')
                    ->assertRouteIs('login')
                    ->value('#email', '$user->email')
                    ->value('#password', 'password')
                    ->click('#connexion')
                    ->assertRouteIs('home')
                    ->screenshot('1-connexion');

            // 2 - Erreur création de grille
            $browser->click('#ajouterGrille')
                    ->assertSee('Le champs nom est requis.')
                    ->assertSee('Le champs cours est requis.')
                    ->screenshot('2-erreur-grille');

            // 3 - Création d'une grille dans /home
            $browser->value('#nom', 'Grille de test')
                    ->value('#cours', 'Cours de test')
                    ->click('#ajouterGrille')
                    ->screenshot('3-creation-grille');

            // 4 - Modification de la grille
            $browser->assertSee('Grille de test')
                    ->value('#nom-grille', 'Nouveau nom grille')
                    ->value('#cours-grille', 'Nouveau nom cours')
                    ->script('$("#cours-grille").focusout();');
            $browser->assertSee('Nouveau nom grille')
                    ->assertValue('#cours-grille', 'Nouveau nom cours')
                    ->screenshot('4-modification-grille');

            // 5 - Ajout d'une compétence à la grille
            $browser->value('#createNomComp', "Nom de la compétence 1")
                    ->value('#createDescComp', "Description de la compétence 1")
                    ->value('#createPondComp', 20)
                    ->click('#ajoutComp')
                    ->screenshot('5-ajout-competence');

            // 6 - Erreur création de compétence : nom, description et pondération requise
            $browser->click('#ajoutComp')
                    ->assertSee('Le champs nom est requis.')
                    ->assertSee('Le champs description est requis.')
                    ->assertSee('Le champs ponderation est requis.')
                    ->screenshot('6-erreur-comp');

            // 7 - Ajout d'une compétence menant le total de la pondération à 100
            $browser->value('#createNomComp', "Nom de la compétence 2")
                    ->value('#createDescComp', "Description de la compétence 2")
                    ->value('#createPondComp', 80)
                    ->click('#ajoutComp');
            $browser->value('#total-ponderation', 100)
                    ->screenshot('7-ajout-competence');

            // 8 - Erreur ajout pondération trop haute
            $browser->value('#createNomComp', "Nom de la compétence 3")
                    ->value('#createDescComp', "Description de la compétence 3")
                    ->value('#createPondComp', 10)
                    ->click('#ajoutComp')
                    ->assertSee('The ponderation may not be greater than')
                    ->screenshot('8-ajout-competence');

            // 9 - Suppression d'une compétence
            $browser->click('#deleteComp')
                    ->assertDontSee("Nom de la compétence 1")
                    ->screenshot('9-suppression-competence');

            // 10 - Ajout de deux critères à la grille
            $browser->value('#cote', 0.9)
                    ->value('#descCritere', "Excellent")
                    ->click('#ajoutCrit')
                    ->value('#cote', 0.25)
                    ->value('#descCritere', "Mauvais")
                    ->click('#ajoutCrit')
                    ->assertSee("Mauvais")
                    ->screenshot('10-ajout-critere');

            // 11 - Erreur création d'un critère
            $browser->click('#ajoutCrit')
                    ->assertSee('Le champs cote est requis.')
                    ->assertSee('Le champs desc critere est requis.')
                    ->screenshot('11-erreur-crit');

            // 12 - Suppression d'un critère
            $browser->click('#deleteCrit')
                    ->assertDontSee('Excellent')
                    ->screenshot('12-suppression-critere');

            // 13 - Modification d'une compétence (nom et description)'
            $browser->value('#nomComp', 'Nouveau nom compétence')
                    ->value('#descComp', 'Nouvelle description competence')
                    ->script('$("#descComp").focusout();');
            $browser->assertValue('#nomComp', 'Nouveau nom compétence')
                    ->screenshot('13-modification-competence');

            // 14 - Modification d'une compétence (ponderation)'
            $browser->value('#ponderation', 40)
                    ->script('$("#ponderation").focusout();');
            $browser->assertValue('#ponderation', 40)
                    ->screenshot('14-modification-competence-ponderation');

            // 15 - Ajout d'une correction
            $browser->value('#correction-nom', 'Tremblay')
                    ->value('#correction-prenom', 'John')
                    ->value('#correction-da', '1234567')
                    ->click('#ajoutCorrection')
                    ->assertSee('1234567 - Tremblay, John')
                    ->screenshot('15-ajout-correction');

            // 16 - Erreur ajout d'une correction (pas de nom, de prénom et de da)
            $browser->click('#ajoutCorrection')
                    ->assertSee('Le champs correction-nom est requis')
                    ->assertSee('Le champs correction-prenom est requis')
                    ->assertSee('Le champs correction-da est requis')
                    ->screenshot('16-erreur-ajout-correction-aucune-entrees');

            // 17 - Erreur ajout d'une correction (pas de da)
            $browser->value('#correction-nom', 'Fortin')
                    ->value('#correction-prenom', 'JF')
                    ->click('#ajoutCorrection')
                    ->assertSee('Le champs correction-da est requis')
                    ->screenshot('17-erreur-ajout-correction-aucune-da');

            // 18 - Suppression d'une correction
            $browser->click('#supprCorrection')
                    ->assertDontSee('1234567 - Tremblay, John')
                    ->screenshot('18-erreur-suppression-correction');

            //Test non fonctionnel
            // 19 - Importation d'un fichier .csv contenant des corrections
        //     $browser->click('#fichier')
        //             ->attach('file', storage_path("resources/ListeEtudiants_cours420515FX_gr00001.csv"))
        //             ->click('#btnImporterCorrections')
        //             ->assertSee('1234568 - Boisvert, Mathieu')
        //             ->screenshot('19-importation-fichier-csv');

            // 20 - Modification d'une correction (note)
            $browser->value('#correction-nom', 'Tremblay')
                    ->value('#correction-prenom', 'John')
                    ->value('#correction-da', '1234567')
                    ->click('#ajoutCorrection')
                    ->click('#corrigerCorrection')
                    ->value('#note', 20)
                    ->script('$("#note").focusout();');
            $browser->assertValue('#note', 20)
                    ->assertSeeIn('#noteTotal', 20)
                    ->screenshot('20-modification-correction-note');

            // 21 - Modification d'une correction (description)
            $browser->value('#descCorr', 'Description de la correction')
                    ->script('$("#descCorr").focusout();');
            $browser->assertValue('#descCorr', 'Description de la correction')
                    ->screenshot('21-modification-correction-desc');

            // 22 - Modification d'une correction à l'aide d'un critère
            $browser->select('#selectCrit', 0.25)
                    ->assertValue('#note', 10)
                    ->screenshot('22-modif-correction-critere');

            // 23 - Erreur de modification d'une correction avec une mauvaise note
            $browser->value('#note', 'f')
                    ->script('$("#note").focusout();');
            $browser->assertValue('#note', 0)
                    ->screenshot('23-erreur-modification-correction-note');

            // 24 - Tri d'une autre façon que celle de base
            $browser->visit('/home')
                    ->value('#nom', 'Deuxieme grille')
                    ->value('#cours', 'Deuxieme grille')
                    ->click('#ajouterGrille')
                    ->visit('/home')
                    ->select('#selectTri', 'nom')
                    ->assertSeeIn('#nomGrille', "Nouveau nom grille")
                    ->screenshot('24-tri-nom');

            // 25 - Tri d'un autre ordre que celui de base
            $browser->select('#directionTri', 'Asc')
                    ->assertSeeIn('#nomGrille', "Deuxieme grille")
                    ->screenshot('25-tri-ascendant');

            // Fin - Supression de la grille
            $browser->visit('/home')
                    ->click('#mesGrilles .grille:first-child .delete-btn')
                    ->screenshot('FIN-supression-grille');

        });
    }
}
