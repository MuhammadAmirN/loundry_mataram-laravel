<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'service'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->payment, fn($q) => $q->where('payment_status', $request->payment))
            ->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,done,picked_up,cancelled']);
        $order->update(['status' => $request->status]);

        if ($request->status === 'picked_up') {
            $order->update(['picked_up_at' => now()]);
        }

        return back()->with('success', 'Status order berhasil diupdate!');
    }

    public function updatePayment(Request $request, Order $order)
    {
        $request->validate(['payment_status' => 'required|in:paid,unpaid']);
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Status pembayaran berhasil diupdate!');
    }
}