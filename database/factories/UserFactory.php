<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name'  => $this->faker->firstName,
            // Always provide some middle name
            'middle_name' => $this->faker->firstName,
            'last_name'   => $this->faker->lastName,
            'email'       => $this->faker->unique()->safeEmail,
            'password'    => Hash::make('password'),
        ];
    }
}