<?php

namespace App\Http\Entity;

class Invoice
{
    public int $id;
    public int $invoice_id;
    public string $invoice_date;
    public string $due_date;
    public string $order_number;
    public float $total;
    public float $total_vat;
    public float $total_without_vat;
    public string $note;
    public string $supplier;

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

    public function getInvoiceDate(): string
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(string $invoice_date): void
    {
        $this->invoice_date = $invoice_date;
    }

    public function getDueDate(): string
    {
        return $this->due_date;
    }

    public function setDueDate(string $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function getOrderNumber(): string
    {
        return $this->order_number;
    }

    public function setOrderNumber(string $order_number): void
    {
        $this->order_number = $order_number;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function getTotalVat(): float
    {
        return $this->total_vat;
    }

    public function setTotalVat(float $total_vat): void
    {
        $this->total_vat = $total_vat;
    }

    public function getTotalWithoutVat(): float
    {
        return $this->total_without_vat;
    }

    public function setTotalWithoutVat(float $total_without_vat): void
    {
        $this->total_without_vat = $total_without_vat;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function getSupplier(): string
    {
        return $this->supplier;
    }

    public function setSupplier(string $supplier): void
    {
        $this->supplier = $supplier;
    }

}
