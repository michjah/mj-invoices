<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Entity\Invoice;
use App\Http\Entity\InvoiceItem;
use App\Models\Invoices as InvoiceModel;
use App\Models\InvoiceItem as InvoiceItemModel;
use App\Services\PdfGenerateService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceModel $invoices,
        private readonly InvoiceItemModel $invoiceItem
    ) {}

    public function getById(int $invoiceId) {
        return response()->json(
            $this->invoices
                ->with('supplier')
                ->with('customer')
                ->with('items')
                ->where('invoice_id', '=', $invoiceId)
            ->first()
        );
    }

    public function pdfPreview(InvoiceModel $invoice)
    {
        $invoiceData = $invoice
            ->with('supplier')
            ->with('customer')
            ->with('items')
            ->where('invoice_id', $invoice->invoice_id)
            ->first()
            ->toArray();

        $pdfGenerator = new PdfGenerateService($invoiceData);
        $pdf = $pdfGenerator->generate($invoiceData);

        $filename = 'Invoice_' . $invoice['invoice_id'] . '_' . $invoice['status'] . '.pdf';
        return response()->streamDownload(fn() => $pdf->Output($filename, 'I'));
    }

    public function pdfProcess(InvoiceModel $invoice) {
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->invoices->rules);

        return DB::transaction(function () use ($validated) {

            $items = $validated['items'] ?? [];
            unset($validated['items']);

            $invoice = InvoiceModel::create($validated);

            if (!empty($items)) {
                $invoice->items()->createMany($items);
            }

            return response()->json(
                $invoice->load('items'),
                201
            );
        });
    }

    public function update(Request $request) {
        $validatedData = $request->validate([]);
        return response()->json($this->invoices->update($validatedData));
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request) {
        $page = $request->get('page') ?? 1;
        $itemsPerPage = $request->get('itemsPerPage') ?? 10;

        $builder = $this->invoices->with('supplier')->with('customer')->with('items');

        // filtering
        $filter = $request->get('filter') ?? null;
        if (!empty($filter)) {
            foreach ($filter as $item) {
                if ($item['operator'] === 'like') {
                    $item['value'] = "%{$item['value']}%";
                }
                $builder = $builder->where($item['key'], $item['operator'], $item['value']);
            }
        }

        // sorting
        if (is_array($request->get('sortBy'))) {
            $sortBy = $request->get('sortBy');
        } else {
            $sortBy = ['key' => 'invoice_id', 'order' => 'asc'];
        }
        $builder = $builder->orderBy($sortBy['key'], $sortBy['order']);

        return $builder->paginate($itemsPerPage, ['*'], 'page', $page);
    }

    public function add(array $data) {
        $invoice = new Invoice();
        $invoice->invoice_id = $data['invoice_id'];
        $invoice->invoice_date = $data['invoice_date'];
        $invoice->due_date = $data['due_date'];
        $invoice->order_id = $data['order_id'];
        $invoice->total = $data['total'];
        $invoice->total_vat = $data['total_vat'];
        $invoice->total_without_vat = $data['total_without_vat'];
        $invoice->note = $data['note'];
        $invoice->supplier = $data['supplier'];
        $invoiceId = $this->invoices->addNew($invoice);

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $invoiceItem = new InvoiceItem();
                $invoiceItem->setInvoiceId($invoiceId);
                $invoiceItem->setText($item['text']);
                $invoiceItem->setAmount($item['amount']);
                $invoiceItem->setPrice($item['price']);
                $invoiceItem->setVat($item['vat']);
                $invoiceItem->setTotal($item['total']);
                $invoiceItem->setTax($item['tax'] ?? 21);
                $invoiceItem->setCurrency($item['currency']);
                $this->invoiceItem->addNew($invoiceItem);
            }
        }

        return response()->json($invoice);
    }
}
