<?php

namespace Database\Factories;
use App\Models\State;
use App\Models\Country;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\State>
 */
class StateFactory extends Factory
{
    protected $model = State::class;
    public function definition()
    {
        $country = Country::inRandomOrder()->first(); // get a random country
        return [
            'name' => $this->faker->state,
            'abrv' => $this->faker->stateAbbr,
            'country_id' => $country->id // assign the country id to the state
        ];
    }
}
