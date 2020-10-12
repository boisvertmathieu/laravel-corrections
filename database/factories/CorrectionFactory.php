<?php

namespace Database\Factories;

use App\Models\Correction;
use Illuminate\Database\Eloquent\Factories\Factory;

class CorrectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Correction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->date('Y-m-d H:i:s', 'now');

        return [
            'nom' => $this->faker->name(),
            'prenom'=>$this->faker->name(),
            'da' => $this->faker->numberBetween(1000000, 9999999),
            'grille_id' => $this->faker->numberBetween(1, 16),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
