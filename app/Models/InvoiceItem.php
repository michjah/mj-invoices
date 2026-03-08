<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoices_item';

    protected $fillable = [
        'text',
        'amount',
        'price',
        'vat',
        'total',
        'tax',
        'currency'
    ];

    public $timestamps = false;

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }
}
