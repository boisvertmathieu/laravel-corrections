<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use App\Models\Correction;
use App\Models\Grille;
use App\Models\Critere;
use App\Models\Note;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Symfony\Component\Console\Input\Input;
use function MongoDB\BSON\toJSON;
use function Symfony\Component\String\b;

class CorrectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grille = Auth::grille();
        $corrections = Correction::where('grille_id', '=', $grille->id)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('grille', compact('corrections'));
    }

    /**
     * Retourne toute les corrections dont l'id de la grille correspond à l'id en paramètre
     */
    public static function show($id)
    {
        $correction = Correction::where('grille_id', '=', $id)
            ->orderBy('id', 'asc')
            ->get();

        return $correction;
    }

    /**
     * Permet d'afficher les détails d'une correction
     */
    public function showDetails($grilleId, $correctionId)
    {
        $grille = Grille::findOrFail($grilleId);
        $correction = Correction::findOrFail($correctionId);
        $competences = Competence::where('grille_id', '=', $grilleId)
            ->orderBy('id', 'asc')
            ->get();
        $notes = Note::where('correction_id', '=', $correctionId)
            ->orderBy('competence_id', 'asc')
            ->get();

        $i = 0;
        $listeCriteres = null;
        foreach ($competences as $competence) {
            $listeCriteres[$i] = CritereController::show($competence->id);
            $i++;
        }

        return view('correction', compact('grille', 'competences', 'correction', 'notes', 'listeCriteres'));
    }

    /**
     * Permet de créer une correction et de l'insérer dans la bd
     */
    public function createCorrection(Request $request, $grille_id)
    {
        $request->validate([
            'correction-nom' => 'required',
            'correction-prenom' => 'required',
            'correction-da' => 'required|numeric|min:1000000|max:9999999',
        ]);
        $correction = new Correction([
            'nom' => $request->get('correction-nom'),
            'prenom' => $request->get('correction-prenom'),
            'da' => $request->get('correction-da'),
            'grille_id' => $grille_id
        ]);

        $correction->save();

        return redirect(route('grille.show', $grille_id));
    }

    /**
     * Permet de mettre à jour une note d'une correction
     */
    public function updateNote(Request $request, $idCorrection, $idCompetence, $idNote = null)
    {

        if ($idNote != null) {
            $note = Note::findOrFail($idNote);
            $correction = Correction::findOrFail($note->correction_id);
            $competence = Competence::findOrFail($idCompetence);

            if (isset($request->note) && is_numeric($request->note) && $request->note <= $competence->ponderation && $request->note >= 0) {
                $note->note = $request->note;
            } else if (isset($request->descCorr)) {
                $note->description = $request->descCorr;
            } else if ($request->selectCrit != "null") {
                $note->note = $request->selectCrit * $competence->ponderation;
            }
            $note->save();
        } else {
            $correction = Correction::findOrFail($idCorrection);
            $competence = Competence::findOrFail($idCompetence);
            if (isset($request->note)) {
                if (is_numeric($request->note) && $request->note <= $competence->ponderation && $request->note >= 0) {
                    $note = new Note([
                        'correction_id' => $correction->id,
                        'competence_id' => $competence->id,
                        'note' => $request->note,
                        'description' => ""
                    ]);
                    $note->save();
                }
            } else if (isset($request->descCorr)) {
                $note = new Note([
                    'correction_id' => $correction->id,
                    'competence_id' => $competence->id,
                    'note' => 0,
                    'description' => $request->descCorr
                ]);
                $note->save();
            } else if ($request->selectCrit != "null") {
                $note = new Note([
                    'correction_id' => $correction->id,
                    'competence_id' => $competence->id,
                    'note' => $request->selectCrit * $competence->ponderation,
                    'description' => ""
                ]);
                $note->save();
            }
        }

        return redirect(route('showDetails', [$correction->grille_id, $correction->id]));
    }

    /**
     * Permet de supprimer une correction de la bd selon l'id de la correction en paramètre
     */
    public function destroy($id)
    {
        $correction = Correction::findOrFail($id);
        $grille = Grille::findOrFail($correction->grille_id);
        $correction->delete();

        return redirect(route('grille.show', $grille->id));
    }

    /**
     * Permet d'importer un fichier csv contenant plusieurs corrections à ajouter à la bd
     */
    public function importationCSV(Request $request, $grille_id)
    {
        //Validation du fichier en request
        $validation = $request->validate([
            'fichier' => 'required|file'
        ]);
        $fichier = $validation['fichier'];

        //Importation des données du fichier dans un array nommé data
        $header = null;
        $data = array();
        //Vérification de la possibilité d'ouvrir le fichier en mode lecture
        if (($handle = fopen($fichier, 'r')) !== false) {
            //Lecture du fichier en débutant par la deuxième ligne (évitant le titre des colonnes)
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = $row;
            }
            fclose($handle);
        }

        //Iteration à travers le tableau de données data et enregistrement des nouvelles corrections dans la bd
        for ($i = 0; $i < count($data); $i++) {
            //Création d'une nouvelle correction
            $correction = new Correction([
                //Encodage en utf-8 des nom et prénoms en cas qu'il y ait présence d'accents
                'nom' => utf8_encode($data[$i][2]),
                'prenom' => utf8_encode($data[$i][3]),
                'da' => $data[$i][0],
                'grille_id' => $grille_id
            ]);
            //Enregistrement dans la bd de la correction
            $correction->save();
        }
        return redirect(route('grille.show', $grille_id))->with('success', 'All good!');
    }
}
