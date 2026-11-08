<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use Carbon\Carbon;

class AdminSalesReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            now()->endOfMonth()->toDateString()
        );

        $orders = Order::where('payment_status', 'sudah_dibayar')
            ->where('order_status', '!=', 'dibatalkan')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->get();

        $orderIds = $orders->pluck('id');

        $totalRevenue = $orders->sum('total');

        $totalTransactions = $orders->count();

        $items = OrderItem::whereIn('order_id', $orderIds)
            ->selectRaw('
                product_id,
                product_name,
                SUM(quantity) as total_qty,
                SUM(subtotal) as total_sales
            ')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->get();

        $totalItemsSold = $items->sum('total_qty');

        return view('admin.sales_report.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTransactions',
            'items',
            'totalItemsSold'
        ));
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input(
            'start_date',
            now()->startOfMonth()->toDateString()
        );

        $endDate = $request->input(
            'end_date',
            now()->endOfMonth()->toDateString()
        );

        return Excel::download(
            new SalesReportExport($startDate, $endDate),
            'Rekap-Penjualan-' . $startDate . '-sd-' . $endDate . '.xlsx'
        );
    }
}
