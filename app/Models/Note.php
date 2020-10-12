<?php

namespace App\Models;

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

}
