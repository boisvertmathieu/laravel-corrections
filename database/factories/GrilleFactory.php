<?php

namespace Database\Factories;

use App\Models\Grille;
use Illuminate\Database\Eloquent\Factories\Factory;

class GrilleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grille::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->date('Y-m-d H:i:s', 'now');

        return [
            'nom' => substr($this->faker->sentence(5, true), 0, -1),
            'cours' => substr($this->faker->sentence(4, true), 0, -1),
            'user_id' => $this->faker->numberBetween(1, 4),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
