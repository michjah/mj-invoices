<?php

namespace App\Http\Entity;

class InvoiceItem
{
    private int $id;
    private int $invoice_id;
    private string $text;
    private float $amount;
    private float $price;
    private int $vat;
    private float $total;
    private float $tax;
    private string $currency;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvoiceId(): int
    {
        return $this->invoice_id;
    }

    public function setInvoiceId(int $invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getVat(): int
    {
        return $this->vat;
    }

    public function setVat(int $vat): void
    {
        $this->vat = $vat;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function setTax(float $tax): void
    {
        $this->tax = $tax;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function toArray(): array
    {
        return [
            'invoices_invoice_id' => $this->invoice_id,
            'text' => $this->text,
            'amount' => $this->amount,
            'price' => $this->price,
            'vat' => $this->vat,
            'total' => $this->total,
        ];
    }
}
