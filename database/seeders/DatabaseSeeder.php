<?php

namespace Database\Seeders;

use App\Models\Contacts;
use App\Models\InvoiceItem;
use App\Models\Invoices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Invoices::factory()
            ->count(2)
            ->for(Contacts::factory(), 'supplier')
            ->for(Contacts::factory(), 'customer')
            ->has(InvoiceItem::factory()->count(5), 'items')
            ->create();
    }
}
