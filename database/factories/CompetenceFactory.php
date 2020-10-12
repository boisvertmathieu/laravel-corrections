<?php

namespace Database\Factories;

use App\Models\Competence;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Competence::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {        
        return [
            'nom' => substr($this->faker->sentence(5, true), 0, -1),
            'ponderation' => $this->faker->numberBetween(10,25),
            'description' => $this->faker->paragraph,
            'grille_id' => $this->faker->numberBetween(2,40)
        ];
    }
}
