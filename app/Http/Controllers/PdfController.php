<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Services\PdfGenerateService;
use tFPDF;

class PdfController extends Controller
{

    public function __construct(
        private readonly TFPDF $tfpdf,
        private readonly Invoices $invoiceModel
    ) {}

    public function index()
    {
        $invoice = $this->invoiceModel->with('supplier')->with('items')->where('invoice_id', '2026001002')->first()->toArray();

        $pdfGenerator = new PdfGenerateService($invoice);
        $pdf = $pdfGenerator->generate($invoice);
        return response()->streamDownload(fn() => $pdf->Output('hello_world.pdf', 'I'));

    }

    public function index2()
    {
        $this->tfpdf->AddFont('DejaVu Serif','','DejaVuSerif.ttf', true);
        $this->tfpdf->AddFont('DejaVu Serif Bold','B','DejaVuSerif-Bold.ttf', true);

        $this->tfpdf->AddPage('P', 'A4');

        $this->tfpdf->SetFont('DejaVu Serif Bold','B',12);
        $this->tfpdf->Cell(140, 10, 'Daňový doklad - FAKTURA', 0);
        $this->tfpdf->Cell(50, 10, 'č. ' . '1234455444', 0, 1, 'R');

        $this->tfpdf->SetFont('DejaVu Serif','',10);

        $this->tfpdf->SetLineWidth(2);
        $this->tfpdf->SetDrawColor(0, 0, 128);

        $this->tfpdf->Line(10, 102, 200, 102);
        $this->tfpdf->Line(10, 145, 200, 145);

        $this->tfpdf->SetLineWidth(0.2);
        $this->tfpdf->SetDrawColor(0, 0, 0);

        $this->tfpdf->Ln(7);

        $this->tfpdf->Cell(95, 10, 'Dodavatel', 0);
        $this->tfpdf->Cell(95, 10, 'Odběratel', 0);

        $this->tfpdf->Ln(10);

        $this->tfpdf->setFont('DejaVu Serif Bold','B',10);
        $this->tfpdf->Cell(95, 8, 'Název dodavatele', 0);
        $this->tfpdf->Cell(95, 8, 'Název odběratele', 0);
        $this->tfpdf->setFont('DejaVu Serif','',10);

        $this->tfpdf->Ln(10);

        $this->tfpdf->Cell(95, 8, 'Dod Ulice 123', 0);
        $this->tfpdf->Cell(95, 8, 'Odb Ulice 123', 0);

        $this->tfpdf->Ln(6);

        $this->tfpdf->Cell(95, 8, 'Dod Město', 0);
        $this->tfpdf->Cell(95, 8, 'Odb Město', 0);

        $this->tfpdf->Ln(6);

        $this->tfpdf->Cell(95, 8, 'Dod PSČ', 0);
        $this->tfpdf->Cell(95, 8, 'Odb PSČ', 0);

        $this->tfpdf->Ln(10);

        $this->tfpdf->Cell(95, 8, 'IČ: ' . '12345678', 0);
        $this->tfpdf->Cell(95, 8, 'IČ: ' . '87654321', 0);

        $this->tfpdf->Ln(6);

        $this->tfpdf->Cell(95, 8, 'DIČ: ' . 'CZ12345678', 0);
        $this->tfpdf->Cell(95, 8, 'DIČ: ' . 'CZ87654321', 0);

        $this->tfpdf->Ln(10);

        $this->tfpdf->MultiCell(95, 6, 'Firma je zapsaná do obchodního rejstříku vedeného krajským soudem v Praze, oddíl B. vložka 123456', 0);

        $this->tfpdf->Ln(10);

        $this->tfpdf->Cell(95, 8, 'Platební údaje', 0);

        $this->tfpdf->Ln(8);

        $this->tfpdf->Cell(40, 8, 'Bankovní spojení', 0);
        $this->tfpdf->Cell(55, 8, 'ČSOB', 0);
        $this->tfpdf->Cell(40, 8, 'Datum vystavení', 0);
        $this->tfpdf->Cell(55, 8, '12.01.2023', 0, false, 'R');
        $this->tfpdf->Ln(8);
        $this->tfpdf->Cell(40, 8, 'Číslo účtu', 0);
        $this->tfpdf->SetFont('DejaVu Serif Bold','B',10);
        $this->tfpdf->Cell(55, 8, '1888888/0300', 0);
        $this->tfpdf->SetFont('DejaVu Serif','',10);
        $this->tfpdf->Cell(40, 8, 'Datum zdaň. období', 0);
        $this->tfpdf->Cell(55, 8, '12.01.2026', 0, false, 'R');
        $this->tfpdf->Ln(8);
        $this->tfpdf->Cell(40, 8, 'Variabilní symbol', 0);
        $this->tfpdf->SetFont('DejaVu Serif Bold','B',10);
        $this->tfpdf->Cell(55, 8, '1234455444', 0);
        $this->tfpdf->Cell(40, 8, 'Datum splatnosti', 0);
        $this->tfpdf->Cell(55, 8, '12.03.2026', 0, false, 'R');
        $this->tfpdf->SetFont('DejaVu Serif','',10);

        $this->tfpdf->Ln(20);

        $this->tfpdf->Cell(50, 8, 'Položka', 0, false, 'L');
        $this->tfpdf->Cell(18, 8, 'Počet MJ', 0, false,'R');
        $this->tfpdf->Cell(25, 8, 'Cena za MJ', 0, false, 'R');
        $this->tfpdf->Cell(35, 8, 'Celkem bez DPH', 0, false, 'R');
        $this->tfpdf->Cell(31, 8, 'Sazba DPH', 0, false, 'R');
        $this->tfpdf->Cell(31, 8, 'Celkem s DPH', 0, false, 'R');;

        for ($i = 0; $i < 30; $i++) {
            $this->tfpdf->Ln(8);
            $this->tfpdf->Cell(50, 8, 'Programátorské práce '.$i.' hodina', 0, false, 'L');
            $this->tfpdf->Cell(18, 8, '10', 0, false,'R');
            $this->tfpdf->Cell(25, 8, '390' . ' Kč', 0, false, 'R');
            $this->tfpdf->Cell(35, 8, '3900' . ' Kč', 0, false, 'R');
            $this->tfpdf->Cell(31, 8, '21%', 0, false, 'R');
            $this->tfpdf->Cell(31, 8, '4719' . ' Kč', 0, false, 'R');;
        }

        $this->tfpdf->Ln(20);

        $this->tfpdf->Cell(95, 8, '', 0);
        $this->tfpdf->Cell(40, 8, 'Celkem bez DPH', 0);
        $this->tfpdf->Cell(55, 8, '99 999' . ' Kč', 0, false, 'R');
        $this->tfpdf->Ln(8);
        $this->tfpdf->Cell(95, 8, '', 0);
        $this->tfpdf->Cell(40, 8, 'DPH 21%', 0);
        $this->tfpdf->Cell(55, 8, '12 222' . ' Kč', 0, false, 'R');
        $this->tfpdf->Ln(8);
        $this->tfpdf->setFont('DejaVu Serif Bold','B',10);
        $this->tfpdf->Cell(95, 8, '', 0);
        $this->tfpdf->Cell(40, 8, 'Celkem k úhradě', 0);
        $this->tfpdf->Cell(55, 8, '99 999' . ' Kč', 0, false, 'R');
        $this->tfpdf->setFont('DejaVu Serif','',10);

        $this->tfpdf->Ln(20);

        $this->tfpdf->Footer();
        $this->tfpdf->Cell(95, 8, 'Vystavil', 0);
        $this->tfpdf->Ln(8);
        $this->tfpdf->Cell(40, 8, 'Michal Jahoda', 0);


        return response()->streamDownload(fn() => $this->tfpdf->Output('hello_world.pdf', 'I'));

    }
}
