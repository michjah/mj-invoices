<?php

namespace App\Services;

use App\Models\Invoices;
use tFPDF;

class PdfGenerateService extends tFPDF
{
    private array $invoice;

    public function __construct(array $invoice) {
        $this->invoice = $invoice;

        parent::__construct();

        $this->AddFont('DejaVu Serif','','DejaVuSerif.ttf', true);
        $this->AddFont('DejaVu Serif Bold','B','DejaVuSerif-Bold.ttf', true);
        $this->AddPage();
        $this->SetAuthor('Michal Jahoda');
        $this->SetCreator('Michal Jahoda');
        $this->SetTitle('Faktura');
        $this->SetSubject('Faktura');
        $this->SetKeywords('Faktura');
        $this->AliasNbPages();
    }

    public function header() {
        $this->setY(10);
        $this->SetFont('DejaVu Serif Bold','B',12);
        $this->Cell(140, 10, 'Daňový doklad - FAKTURA', 0);
        $this->Cell(50, 10, 'č. ' . $this->invoice['invoice_id'], 0, 1, 'R');

        $this->SetMargins(10, 10);
        $this->SetLineWidth(2);
        $this->SetDrawColor(0, 0, 128);
        $this->Line(10, 20, 200, 20);
        $this->Ln(5);
    }

    public function footer() {
        $this->SetY(-15);
        $this->SetFont('DejaVu Serif','',8);
        $this->Cell(95, 8, 'Vystavil' . ' Michal Jahoda invoice application', 0);
        $this->Cell(95, 8, 'Strana ' . $this->PageNo() . '/{nb}', 0, false, 'R');
    }

    public function generate(array $invoice): self {
        $this->invoice = $invoice;

        $this->SetLineWidth(2);
        $this->SetDrawColor(0, 0, 128);

        $this->Line(10, 100, 200, 100);
        $this->Line(10, 142, 200, 142);

        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0, 0, 0);

        $this->Cell(95, 10, 'Dodavatel', 0);
        $this->Cell(95, 10, 'Odběratel', 0);

        $this->Ln(10);

        $this->setFont('DejaVu Serif Bold','B',10);
        $this->Cell(95, 8, 'Název dodavatele', 0);
        $this->Cell(95, 8, $invoice['supplier']['name'], 0);
        $this->setFont('DejaVu Serif','',10);

        $this->Ln(10);

        $this->Cell(95, 8, 'Dod Ulice 123', 0);
        $this->Cell(95, 8, $invoice['supplier']['address_street'], 0);

        $this->Ln(6);

        $this->Cell(95, 8, 'Dod Město', 0);
        $this->Cell(95, 8, $invoice['supplier']['address_city'], 0);

        $this->Ln(6);

        $this->Cell(95, 8, 'Dod PSČ', 0);
        $this->Cell(95, 8, $invoice['supplier']['address_zipcode'], 0);

        $this->Ln(10);

        $this->Cell(95, 8, 'IČ: ' . '12345678', 0);
        $this->Cell(95, 8, 'IČ: ' . $invoice['supplier']['ic'], 0);

        $this->Ln(6);

        $this->Cell(95, 8, 'DIČ: ' . 'CZ12345678', 0);
        $this->Cell(95, 8, 'DIČ: ' . $invoice['supplier']['dic'], 0);

        $this->Ln(10);

        $this->MultiCell(95, 6, 'Firma je zapsaná do obchodního rejstříku vedeného krajským soudem v Praze, oddíl B. vložka 123456', 0);

        $this->Ln(10);

        $this->Cell(95, 8, 'Platební údaje', 0);

        $this->Ln(8);

        $this->Cell(40, 8, 'Bankovní spojení', 0);
        $this->Cell(55, 8, 'ČSOB', 0);
        $this->Cell(40, 8, 'Datum vystavení', 0);
        $this->Cell(55, 8, date('d.m.Y', strtotime($invoice['invoice_date'])), 0, false, 'R');
        $this->Ln(8);
        $this->Cell(40, 8, 'Číslo účtu', 0);
        $this->SetFont('DejaVu Serif Bold','B',10);
        $this->Cell(55, 8, '1888888/0300', 0);
        $this->SetFont('DejaVu Serif','',10);
        $this->Cell(40, 8, 'Datum zdaň. období', 0);
        $this->Cell(55, 8, date('d.m.Y', strtotime($invoice['due_date'])), 0, false, 'R');
        $this->Ln(8);
        $this->Cell(40, 8, 'Variabilní symbol', 0);
        $this->SetFont('DejaVu Serif Bold','B',10);
        $this->Cell(55, 8, $invoice['invoice_id'], 0);
        $this->Cell(40, 8, 'Datum splatnosti', 0);
        $this->Cell(55, 8, date('d.m.Y', strtotime($invoice['due_date'])), 0, false, 'R');
        $this->SetFont('DejaVu Serif','',10);

        $this->Ln(20);

        $this->Cell(50, 8, 'Položka', 0, false, 'L');
        $this->Cell(18, 8, 'Počet MJ', 0, false,'R');
        $this->Cell(25, 8, 'Cena za MJ', 0, false, 'R');
        $this->Cell(35, 8, 'Celkem bez DPH', 0, false, 'R');
        $this->Cell(31, 8, 'Sazba DPH', 0, false, 'R');
        $this->Cell(31, 8, 'Celkem s DPH', 0, false, 'R');;

        $this->Ln(8);

        if (!empty($invoice['items'])) {
            foreach ($invoice['items'] as $item) {
                $this->SetFont('DejaVu Serif','',8);

                // šířky sloupců
                $w = [50, 18, 25, 35, 31, 31];

                $lineHeight = 5;

                // spočítáme počet řádků pro text položky
                $nb = $this->NbLines($w[0], $item['text']);
                $rowHeight = $nb * $lineHeight;

                // kontrola zalomení stránky
                if ($this->GetY() + $rowHeight > $this->PageBreakTrigger) {
                    $this->AddPage($this->CurOrientation);
                }

                $x = $this->GetX();
                $y = $this->GetY();

                // === 1. sloupec (víceřádkový text) ===
                $this->MultiCell($w[0], $lineHeight, $item['text'], 0, 'L');

                // vrátíme se nahoru vedle buňky
                $this->SetXY($x + $w[0], $y);

                // === ostatní sloupce ===
                $this->topCell($w[1], $rowHeight, $item['amount'], 0, 'R');
                $this->topCell($w[2], $rowHeight, $this->priceConvert($item['price'], $item['currency']), 0, 'R');
                $this->topCell($w[3], $rowHeight, $this->priceConvert($item['price'] * $item['amount'], $item['currency']), 0, 'R');
                $this->topCell($w[4], $rowHeight, $item['tax'] . '%', 0,  'R');
                $this->topCell($w[5], $rowHeight, $this->priceConvert($item['total'], $item['currency']), 0, 'R');

                // posun na další řádek
                $this->Ln($rowHeight + 2);
            }
        }

        $this->Ln(20);

        $this->Cell(95, 8, '', 0);
        $this->Cell(40, 8, 'Celkem bez DPH', 0);
        $this->Cell(55, 8, $this->priceConvert($invoice['total_price_without_vat'], 'CZK'), 0, false, 'R');
        $this->Ln(8);
        $this->Cell(95, 8, '', 0);
        $this->Cell(40, 8, 'DPH 21%', 0);
        $this->Cell(55, 8, $this->priceConvert($invoice['total_price_vat'], 'CZK'), 0, false, 'R');
        $this->Ln(8);
        $this->setFont('DejaVu Serif Bold','B',10);
        $this->Cell(95, 8, '', 0);
        $this->Cell(40, 8, 'Celkem k úhradě', 0);
        $this->Cell(55, 8, $this->priceConvert($invoice['total_price'], 'CZK'), 0, false, 'R');
        $this->setFont('DejaVu Serif','',10);

        $this->Ln(20);

        return $this;
    }

    private function priceConvert($price, $currency) {
        if ($currency === 'CZK') {
            return number_format($price, 0, '', ' ') . ' ' . $currency;
        } else {
            return number_format($price, 2, ',', ' ') . ' ' . $currency;
        }
    }

    private function NbLines($w, $txt)
    {
        $txt = str_replace("\r", '', $txt);
        $lines = explode("\n", $txt);
        $count = 0;

        foreach ($lines as $line) {

            $lineWidth = $this->GetStringWidth($line);
            $maxWidth = $w - 2 * $this->cMargin;

            if ($lineWidth == 0) {
                $count++;
                continue;
            }

            $count += ceil($lineWidth / $maxWidth);
        }

        return max($count, 1);
    }

    private function topCell($w, $h, $txt, $border=0, $align='L')
    {
        $x = $this->GetX();
        $y = $this->GetY();

        // rámeček
        if ($border) {
            $this->Rect($x, $y, $w, $h);
        }

        // text nahoře
        $this->Cell($w, 5, $txt, 0, 0, $align);

        // posun vedle
        $this->SetXY($x + $w, $y);
    }
}
