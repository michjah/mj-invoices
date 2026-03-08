<?php

namespace Database\Factories;

use App\Models\InvoiceItem;
use App\Models\Invoices;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        return [
            'text' => $this->faker->text(100),
            'amount' => $this->faker->randomFloat(2, 0, 10000),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'vat' => $this->faker->randomFloat(2, 0, 100),
            'total' => $this->faker->randomFloat(2, 0, 1000),
            'tax' => $this->faker->randomElement([21, 10]),
            'currency' => 'CZK',
        ];
    }
}
