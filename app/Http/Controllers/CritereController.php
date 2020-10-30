<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Grille;
use App\Models\Competence;
use App\Models\Critere;


use Auth;
use Hamcrest\Type\IsInteger;

class CritereController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affichage des critères pour une compétence
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $competence = Auth::competence();
        $criteres = Critere::where('competence_id', '=', $competence->id);
        return view('grille', compact('criteres'));
    }

    /**
     * Permet de retourner tous les critères pour une compétence, dont l'id de la compétence correspond
     * à celui en paramètre
     */
    public static function show($id)
    {
        $criteres = Critere::where('competence_id', '=', $id)
        ->orderBy('id', 'asc')
        ->get();

        return $criteres;
    }

    /**
     * Permet de créer un critère pour une compétence et de l'insérer dans la bd
     */
    public function createCritere(Request $request, $competence_id)
    {
        $request->validate([
            'cote' => 'required',
            'descCritere' => 'required',
        ]);

        $critere = new Critere([
            'cote' => $request->get('cote'),
            'description' => $request->get('descCritere'),
            'competence_id' => $competence_id
        ]);

        $critere->save();

        $competence = Competence::findOrFail($critere->competence_id);
        return redirect(route('grille.show', $competence->grille_id));
    }

    /**
     * Permet de supprimer un critère dont l'id correspond à celui en paramètre
     */
    public function destroy($idCritere)
    {
        $critere = Critere::findOrFail($idCritere);
        $competence = Competence::findOrFail($critere->competence_id);
        $critere->delete();

        return redirect(route('grille.show', $competence->grille_id));
    }
}
