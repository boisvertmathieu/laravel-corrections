<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $note = new Note();
        $note->correction_id = 1;
        $note->competence_id = 1;
        $note->note = 20;
        $note->description = "Très bien!";
        $note->save();
    }
}
