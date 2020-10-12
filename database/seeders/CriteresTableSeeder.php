<?php

namespace Database\Seeders;

use App\Models\Critere;
use Illuminate\Database\Seeder;

class CriteresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $critere = new Critere();
        $critere->competence_id = 1;
        $critere->cote = 0.8;
        $critere->description = "Bien";
        $critere->save();
    }
}
