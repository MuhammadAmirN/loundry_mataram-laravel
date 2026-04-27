<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        return view('user.orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity'   => 'required|numeric|min:0.1',
            'notes'      => 'nullable|string|max:500',
        ]);

        $service    = Service::findOrFail($request->service_id);
        $totalPrice = $service->price_per_unit * $request->quantity;

        Order::create([
            'order_code'     => Order::generateCode(),
            'user_id'        => auth()->id(),
            'service_id'     => $request->service_id,
            'quantity'       => $request->quantity,
            'notes'          => $request->notes,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('user.my-orders')
            ->with('success', 'Order berhasil dibuat! Silakan tunggu konfirmasi.');
    }

    public function myOrders()
    {
        $orders = Order::with('service')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('user.orders.my-orders', compact('orders'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        if (!in_array($order->status, ['pending'])) {
            return back()->with('error', 'Order tidak bisa dibatalkan, sudah diproses.');
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Order berhasil dibatalkan.');
    }
}