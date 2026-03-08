<?php

namespace App\Models;

use Database\Factories\InvoicesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    /** @use HasFactory<InvoicesFactory> */
    use HasFactory;

    protected $table = 'invoices';

    public $rules = [
        'invoice_id' => 'required|unique:invoices,invoice_id',
        'invoice_date' => 'required|date',
        'due_date' => 'required|date',
        'duzp_date' => 'required|date',
        'status' => 'required|in:DRAFT,GENERATED,SENT',
        'order_id' => 'nullable|string',
        'total_price' => 'required|numeric',
        'total_price_without_vat' => 'required|numeric',
        'total_price_vat' => 'required|numeric',
        'supplier_id' => 'required|exists:contacts,id',
        'customer_id' => 'required|exists:contacts,id',
        'note' => 'nullable|string',
        'items' => 'array',
        'items.*.text' => 'required|string',
        'items.*.amount' => 'required|numeric',
        'items.*.price' => 'required|numeric',
        'items.*.vat' => 'required|numeric',
        'items.*.total' => 'required|string',
        'items.*.currency' => 'required|string|size:3',
    ];

    protected $fillable = [
        'invoice_id',
        'invoice_date',
        'due_date',
        'duzp_date',
        'status',
        'order_id',
        'note',
        'total_price_without_vat',
        'total_price_vat',
        'total_price',
        'supplier_id',
        'customer_id',
        'invoice_pdf',
    ];

    public $timestamps = false;

    public function supplier()
    {
        return $this->belongsTo(Contacts::class);
    }

    public function customer()
    {
        return $this->belongsTo(Contacts::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function getNextInvoiceNumber()
    {
        return date('Y') . '001' . str_pad($this->get()->count() + 1, 3, '0', STR_PAD_LEFT);
    }
}
