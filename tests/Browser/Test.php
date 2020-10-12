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
                    ->value('#email', $user->email)
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
            $browser->value('#createNomComp', "Nom de la compétence")
                    ->value('#createDescComp', "Description de la compétence")
                    ->value('#createPondComp', 20)
                    ->click('#ajoutComp')
                    ->screenshot('5-ajout-competence');

            // 6 - Erreur création de compétence
            $browser->click('#ajoutComp')
                    ->assertSee('Le champs nom est requis.')
                    ->assertSee('Le champs description est requis.')
                    ->assertSee('Le champs ponderation est requis.')
                    ->screenshot('6-erreur-comp');

            // 7 - Ajout d'un critère à la grille
            $browser->value('#cote', 0.9)
                    ->value('#descCritere', "Excellent")
                    ->click('#ajoutCrit')
                    ->screenshot('7-ajout-critere');

            // 8 - Erreur création d'un critère
            $browser->click('#ajoutCrit')
                    ->assertSee('Le champs cote est requis.')
                    ->assertSee('Le champs desc critere est requis.')
                    ->screenshot('8-erreur-crit');

            // 9 - Suppression d'un critère
            $browser->click('#deleteCrit')
                    ->screenshot('9-suppression-critere');

            // 10 - Modification d'une compétence (nom et description)'
            $browser->value('#nomComp', 'Nouveau nom compétence')
                    ->value('#descComp', 'Nouvelle description competence')
                    ->script('$("#descComp").focusout();');
            $browser->assertValue('#nomComp', 'Nouveau nom compétence')
                    ->screenshot('10-modification-competence');

            // 11 - Modification d'une compétence (ponderation)'
            $browser->value('#ponderation', 40)
                    ->script('$("#ponderation").focusout();');
            $browser->assertValue('#ponderation', 40)
                    ->screenshot('11-modification-competence-ponderation');

            // 12 - Ajout d'une correction
        //     $browser->value('#correction-nom', "Tremblay")
        //             ->value('#correction-prenom', "John")
        //             ->value('#correction-da', "1234567")
        //             ->click('#ajoutCorrection')
        //             ->screenshot('12-ajout-correction');

            // 13 - Ajout d'une note
        //     $browser->value('#note', 20)
        //             ->value('#descCorr', "Explication de la note")
        //             ->script('$("#descCorr").focusout();');
        //     $browser->assertValue('#note', 20)
        //             ->screenshot('13-ajout-note');

            // 14 - Suppression d'une compétence
            $browser->click('#deleteComp')
                    ->screenshot('14-suppression-competence');
            
            // Fin - Supression de la grille 
            $browser->visit('/home')
                    ->click('#mesGrilles .grille:first-child .delete-btn')
                    ->screenshot('FIN-supression-grille');

        });
    }
}