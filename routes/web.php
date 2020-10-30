<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\GrilleController;
use App\Http\Controllers\CritereController;
use App\Http\Controllers\CorrectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* Routes du système d'authentification */
Auth::routes();

/* Route de l'accueil de l'espace authentifié (Mes grilles) */
Route::get('/home/{tri}', [GrilleController::class, 'index'])
    ->name('home');

/* Routes des ressources de base pour la grille */
Route::resource('grille', GrilleController::class)
    ->middleware('auth');

/* Routes des compétences de la grille */
Route::delete('grille/{id}/competence/{comp_id}', [GrilleController::class, 'destroyCompetence'])
    ->middleware('auth')
    ->name('destroyCompetence');

/* Ajout d'une grille */
Route::post('grille', [GrilleController::class, 'store'])
    ->middleware('auth')
    ->name('store');

/* Ajout d'une compétence à une grille */
Route::post('grille/{id}', [GrilleController::class, 'createCompetence'])
    ->middleware('auth')
    ->name('createCompetence');

/* Mise à jour d'une compétence*/
Route::post('grille/{id}/competence/{comp_id}', [GrilleController::class, 'updateCompetence'])
    ->middleware('auth')
    ->name('updateCompetence');

/* Ajout d'un critère à une compétence */
Route::post('critere/{id}', [CritereController::class, 'createCritere',])
    ->middleware('auth')
    ->name('createCritere');

/* Suppression d'un critère */
Route::delete('critere/{id}', [CritereController::class, 'destroy'])
    ->middleware('auth')
    ->name('destroyCritere');

/* Route de la correction d'une compétence d'un élève */
Route::get('grille/{id}/correction/{correction_id}', [CorrectionController::class, 'showDetails'])
    ->name('showDetails');

/* Ajout d'une correction à une grille */
Route::post('correction/{id}', [CorrectionController::class, 'createCorrection'])
    ->middleware('auth')
    ->name('createCorrection');

/* Suppression d'une correction dans une grille */
Route::delete('correction/{id}', [CorrectionController::class, 'destroy'])
    ->middleware('auth')
    ->name('destroyCorrection');

Route::post('/importation/{id}', [CorrectionController::class, 'importationCSV'])
    ->middleware('auth')
    ->name('importationCSV');

/* Mise à jour d'une note*/
Route::post('/correction/{corr_id}/competence/{comp_id}/note/{note_id?}', [CorrectionController::class, 'updateNote'])
    ->middleware('auth')
    ->name('updateNote');

/*
ATTENTION : afin de retirer des routes erronées
mises en cache, utiliser la commande suivante :
php artisan route:cache
*/
