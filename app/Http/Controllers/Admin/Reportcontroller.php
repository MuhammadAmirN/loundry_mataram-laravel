<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'month');
        $date   = $request->get('date', now()->format('Y-m'));

        $query = Order::with(['service', 'user'])
            ->where('payment_status', 'paid')
            ->whereNotIn('status', ['cancelled']);

        switch ($filter) {
            case 'day':
                $query->whereDate('created_at', $request->get('date', today()));
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    Carbon::parse($date)->startOfWeek(),
                    Carbon::parse($date)->endOfWeek(),
                ]);
                break;
            case 'month':
                $query->whereYear('created_at', Carbon::parse($date)->year)
                      ->whereMonth('created_at', Carbon::parse($date)->month);
                break;
            case 'year':
                $query->whereYear('created_at', $request->get('date', now()->year));
                break;
        }

        $orders      = $query->latest()->get();
        $totalIncome = $orders->sum('total_price');
        $totalOrders = $orders->count();

        // Pendapatan per service
        $byService = $orders->groupBy('service.name')->map(fn($g) => [
            'count'  => $g->count(),
            'income' => $g->sum('total_price'),
        ]);

        return view('admin.reports.index', compact('orders', 'totalIncome', 'totalOrders', 'byService', 'filter', 'date'));
    }
}