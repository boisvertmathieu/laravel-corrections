<?php

namespace Database\Factories;

use App\Models\Critere;
use Illuminate\Database\Eloquent\Factories\Factory;

class CritereFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = critere::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->date('Y-m-d H:i:s', 'now');

        return [
            'cote' => $this->faker->randomFloat(2, 0, 1),
            'description' => substr($this->faker->sentence(4, true), 0, -1),
            'competence_id' => $this->faker->numberBetween(1, 16),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
