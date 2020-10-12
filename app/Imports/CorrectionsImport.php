<?php

namespace App\Imports;

use App\Models\Correction;
use Maatwebsite\Excel\Concerns\ToModel;

class CorrectionsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Correction([
            'nom' => $row[0],
            'prenom' => $row[2],
            'da' => $row[3]
        ]);
    }
}
