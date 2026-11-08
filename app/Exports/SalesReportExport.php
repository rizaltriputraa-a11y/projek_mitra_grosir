<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    ShouldAutoSize,
    WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        $orders = Order::where('payment_status', 'sudah_dibayar')
            ->where('order_status', '!=', 'dibatalkan')
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->pluck('id');

        $items = OrderItem::whereIn('order_id', $orders)
            ->selectRaw('
                product_id,
                product_name,
                SUM(quantity) as total_qty,
                SUM(subtotal) as total_sales
            ')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->get();

        $totalQty = $items->sum('total_qty');
        $totalSales = $items->sum('total_sales');

        $items->push((object) [
            'product_name' => 'TOTAL KESELURUHAN',
            'total_qty' => $totalQty,
            'total_sales' => $totalSales,
            'is_total' => true
        ]);

        return $items;
    }

    public function headings(): array
    {
        return [
            ['REKAP PENJUALAN MITRA GROSIR SURABAYA'],
            ['Periode: ' . $this->startDate . ' s/d ' . $this->endDate],
            [],
            [
                'Nama Produk',
                'Total Terjual (Karton)',
                'Total Pendapatan (Rp)'
            ]
        ];
    }

    public function map($item): array
    {
        return [
            $item->product_name,
            $item->total_qty,
            $item->total_sales
        ];
    }

    public function title(): string
    {
        return 'Rekap Penjualan';
    }

    public function styles(Worksheet $sheet): ?array
    {
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');

        $sheet->getStyle('A1:C2')->getAlignment()
            ->setHorizontal('center');

        $sheet->getStyle('A1')->getFont()
            ->setBold(true)
            ->setSize(14);

        $sheet->getStyle('A4:C4')->getFont()
            ->setBold(true);

        $sheet->getStyle('A4:C4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FFD9E1F2');

        $lastRow = $sheet->getHighestRow();

        if ($lastRow >= 5) {
            $sheet->getStyle('C5:C' . $lastRow)
                ->getNumberFormat()
                ->setFormatCode('"Rp" #,##0');

            $sheet->getStyle('A' . $lastRow . ':C' . $lastRow)
                ->getFont()
                ->setBold(true);
        }

        return [];
    }
}
