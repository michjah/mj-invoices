<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contacts>
 */
class ContactsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'ic' => fake()->numerify('###########'),
            'dic' => fake()->randomAscii(),
            'contact_person' => fake()->name(),
            'contact_person_email' => fake()->unique()->safeEmail(),
            'contact_person_phone' => fake()->phoneNumber(),
            'mail' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address_city' => fake()->city(),
            'address_street' => fake()->streetAddress(),
            'address_zipcode' => fake()->postcode(),
            'bank_swift' => fake()->swiftBicNumber(),
            'bank_iban' => fake()->iban(),
            'bank_number' => fake()->numerify('#######/####'),
            'note' => fake()->text(),
            'currency' => 'CZK',
        ];
    }
}
