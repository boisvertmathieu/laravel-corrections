<?php

namespace App\Models;

use App\Http\Controllers\CorrectionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /* Indique que le modèle à un "factorie" associé, dans database/factories */
    use HasFactory;

    /* Les attributs accessibles du modèle */
    protected $fillable = [
        'correction_id',
        'competence_id',
        'note',
        'description'
    ];

    public static function getAllNotes($correction_id)
    {
        $notes = Note::where('correction_id', '=', $correction_id)->get();

        //Si il n'y a pas de notes, on retourne false
        if (empty($notes))
            return false;

        //Création d'un tableau contenant toute les descriptions des notes
        $descNotes = array();

        foreach ($notes as $n) {
            //Vérification de si la description contient plusieurs ligne (plusieurs point)
            if (strpos($n['description'], "\r\n") == false) {
                //Si la note n'est pas vide, on vérifie si elle est déjà dans le tableau, si non, on l'ajoute du tableau
                if (!empty($n['description'])) {
                    if (!in_array($n['description'], $descNotes)) {
                        $descNotes[] = $n['description'];
                    }
                }
            } else {
                // La note contient plus d'une ligne
                $tab_temp = explode("\r\n", $n['description']);

                //Ajout de chaque item de la note dans le tableau des descriptions de notes (si la valeur
                //n'est pas déjà présente)
                foreach ($tab_temp as $item) {
                    if (!in_array($item, $descNotes)) {
                        $descNotes[] = $item;
                    }
                }
            }
        }

        //Retour des valeurs
        return $descNotes;
    }

}
