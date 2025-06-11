<?php

namespace Database\Factories;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;
    public function definition()
    {
        $state = State::inRandomOrder()->first();

        return [
            'name' => $this->faker->city,
            'abrv' => $this->faker->stateAbbr,
            'state_id' => $state->id,
            'country_id' => $state->country_id,
        ];
    }
}
