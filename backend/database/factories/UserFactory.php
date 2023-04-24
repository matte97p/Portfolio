<?php

namespace Database\Factories;

use App\Models\UsersCurrent as User;
use Illuminate\Support\Str;
use Faker\Provider\it_IT\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->name(),
            'taxid' => Person::taxId(),
            'gender' => fake()->randomElement(['m', 'f']),
            'birth_date' => fake()->unique()->dateTimeBetween('-70 years', '-19 years')->format('Y-m-d'),
        ];
    }
}
