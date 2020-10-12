<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grille extends Model
{
    /* Indique que le modèle à un "factorie" associé, dans database/factories */
    use HasFactory;

    /* Les attributs accessibles du modèle */
    protected $fillable = [
        'nom',
        'cours',
        'user_id'
    ];

    public static function getTotalPoints($id)
    {
        // @TODO Implémenter la méthode permettant de calculer le total des points des compétences
        $grille = Grille::findOrFail($id);
        $competences = Competence::where('grille_id', '=', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        $totalPonderation = 0;

        foreach ($competences as $competence) {
            $totalPonderation = $totalPonderation + $competence->ponderation;
        }

        return $totalPonderation;
    }
}
