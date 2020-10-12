<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correction extends Model
{
    /* Indique que le modèle à un "factorie" associé, dans database/factories */
    use HasFactory;

    /* Les attributs accessibles du modèle */
    protected $fillable = [
        'nom',
        'prenom',
        'da',
        'grille_id'
    ];

}
