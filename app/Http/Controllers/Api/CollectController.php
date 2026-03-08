<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoices;

class CollectController
{
    public function __construct(private Invoices $invoices) {}
    public function index()
    {
        return response()->json(
            [
                'nextInvoiceNumber' => $this->invoices->getNextInvoiceNumber(),
                'currencies' => [
                    [
                        'key' => 'CZK',
                        'title' => 'CZK',
                    ], [
                        'key' => 'EUR',
                        'title' => 'EUR'
                    ]
                ],
                'taxes' => [
                    [
                        'key' => 0,
                        'title' => '0%',
                    ],
                    [
                        'key' => 12,
                        'title' => '12%',
                    ],
                    [
                        'key' => 21,
                        'title' => '21%',
                    ],
                ]
            ]
        );
    }
}
