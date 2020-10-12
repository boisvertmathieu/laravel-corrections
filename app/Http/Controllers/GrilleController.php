<?php

namespace App\Http\Controllers;

use App\Models\Correction;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Grille;
use App\Models\Competence;

use Auth;


class GrilleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affichage des grilles pour un utilisateur
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        $grilles = Grille::where('user_id', '=', $user->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(6);
        return view('home', compact('grilles'));
    }

    /**
     * Affichage du formulaire de création d'une grille
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Formulaire de création dans la vue home
    }

    /**
     * Enregistrement d'une nouvelle grille
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'cours' => 'required'
        ]);

        $user = Auth::user();

        $grille = new Grille([
            'nom' => $request->get('nom'),
            'cours' => $request->get('cours'),
            'user_id' => $user->id,
        ]);
        $grille->save();
        return redirect(route('grille.show', $grille->id));
    }

    /**
     * Affichage d'une grille spécifique
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grille = Grille::findOrFail($id);
        $competences = Competence::where('grille_id', '=', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        $listeCriteres = array();
        $i = 0;
        foreach($competences as $competence)
        {
            $listeCriteres[$i] = CritereController::show($competence->id);
            $i++;
        }

        $corrections = Correction::where('grille_id', '=', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('grille', compact('grille', 'competences', 'listeCriteres', 'corrections'));
    }

    /**
     * Affichage du formulaire de modification d'une grille
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // À même la grille
    }

    /**
     * Mise à jour de la grille (nom et cours)
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required',
            'cours' => 'required'
        ]);

        $grille = Grille::findOrFail($id);
        $grille->nom = $request->nom;
        $grille->cours = $request->cours;
        $grille->save();

        return redirect(route('grille.show', $grille->id));
    }

    /**
     * Supression d'une grille spécifique
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Grille::findOrFail($id)->delete();
        Competence::where('grille_id', '=', $id)->delete();
        return redirect(route('home'));
    }


    /**
     *
     * COMPÉTENCES D'UNE GRILLE
     *
     * */

    /**
     * Créer une compétence dans la grille.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function createCompetence(Request $request, $grille_id)
    {
        // @TODO : Permettre l'ajout d'une compétence sur une grille

        //Récupération de la grille afin de déterminer le total des pondérations
        $total = 100 - (int)Grille::getTotalPoints($grille_id);

        $request->validate([
            'nom' => 'required',
            'description' => 'required',
            'ponderation' => 'required|numeric|max:'.$total
        ]);

        $competence = new Competence([
            'nom' => $request->get('nom'),
            'description' => $request->get('description'),
            'ponderation' => $request->get('ponderation'),
            'grille_id' => $grille_id
        ]);

        $competence->save();

        return redirect(route('grille.show', $grille_id));

    }

    /**
     * Mise à jour d'une compétence.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateCompetence(Request $request, $idGrille, $idComp)
    {
        // @TODO : Permettre la modification d'une compétence sur une grille

        $competence = Competence::findOrFail($idComp);
        $total = 100 - (int)Grille::getTotalPoints($idGrille);

        if($request->ponderation)
        {
            if($total < $request->ponderation - $competence->ponderation)
            {
                return redirect(route('grille.show', $idGrille));
            }
            $competence->ponderation = $request->ponderation;
        }
        else if($request->ponderation == "0")
        {
            return redirect(route('grille.show', $idGrille));
        }
        else
        {
            $request->validate([
            'nomComp' => 'required',
            'descComp' => 'required',
            ]);

            $competence->nom = $request->nomComp;
            $competence->description = $request->descComp;
        }

        $competence->save();

        return redirect(route('grille.show', $idGrille));
    }

    /**
     * Supression d'une compétence.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id_grille
     * @param int $id_competence
     * @return \Illuminate\Http\Response
     */
    public function destroyCompetence($id_grille, $id_competence)
    {
        $competence = Competence::findOrFail($id_competence);
        $competence->delete();

        return redirect(route('grille.show', $id_grille));
    }

}
