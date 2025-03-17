<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CompanyAccount;

class CompanyAccountFactory extends Factory
{
    protected $model = CompanyAccount::class;

    public function definition()
    {
        return [
            'user_id'         => 1,
            'company_name'    => $this->faker->company,
            'registered_name' => $this->faker->companySuffix,
            'company_email'   => $this->faker->companyEmail,
            'company_phone'   => $this->faker->phoneNumber,
            'industry'        => $this->faker->word,
            'company_size'    => $this->faker->randomElement(['Small', 'Medium', 'Large']),
        ];
    }
}