<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Office;

class OfficeFactory extends Factory
{
    protected $model = Office::class;

    public function definition()
    {
        return [
            'company_id'        => 1,
            'name'              => $this->faker->company,
            'parent_office_id'  => null,
        ];
    }
}