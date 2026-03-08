<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoices>
 */
class InvoicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => (string) fake()->randomNumber(8),
            'invoice_date' => fake()->date(),
            'due_date' => fake()->date(),
            'duzp_date' => fake()->date(),
            'status' => fake()->randomElement(['DRAFT', 'GENERATED']),
            'order_id' => (string) fake()->randomNumber(8),
            'note' => fake()->text(),
            'total_price_without_vat' => fake()->randomFloat(2, 0, 10000),
            'total_price_vat' => fake()->randomFloat(2, 0, 10000),
            'total_price' => fake()->randomFloat(2, 0, 10000),
            'invoice_pdf' => fake()->randomElement(['pdf', 'html']),
        ];
    }
}
