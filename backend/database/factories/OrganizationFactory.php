<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'code' => strtoupper(fake()->unique()->bothify('ORG###')),
            'email' => fake()->companyEmail(),
            'phone' => fake()->numerify('+1##########'),
            'status' => 'active',
        ];
    }
}
