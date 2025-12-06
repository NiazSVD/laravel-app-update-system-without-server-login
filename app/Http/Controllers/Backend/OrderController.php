<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {

        // Logged-in user
        $user = auth()->user();

        // Collect employee ID and employee code
        $employeeId = $user->id;
        $employeeCode = $user->employee_code;   // MUST exist in users table

        // Cart items
        $cartItems = Cart::with('food')->where('employee_id', $employeeId)->get();

        if ($cartItems->count() == 0) {
            return back()->with('error', 'Cart is empty!');
        }

        // Total amount
        $totalAmount = $cartItems->sum('total_price');

        // Vendor and team
        $vendorId = $cartItems->first()->food->vendor_id;
        $teamId = $user->team_id;


        // Generate unique order number
        $categoryId = $cartItems->first()->food->category_id;
        $orderNumber = 'SV-' . $categoryId . '-' . now()->format('His') . random_int(10,99);

        // Create Order
        $order = Order::create([
            'order_number' => $orderNumber,
            'employee_id' => $employeeId,
            'employee_code' => $employeeCode,   // ← STORED HERE
            'vendor_id' => $vendorId,
            'team_id' => $teamId,
            'total_amount' => $totalAmount,
            'payment_type' => $request->payment_type ?? 'cod',
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'note' => $request->note,
        ]);

        // Notify Admin (user id = 1)
        $admin = User::find(1);
        if ($admin) {
            $admin->notify(new OrderNotification($order));
        }

        // Create Order Items & Reduce Stock
        foreach ($cartItems as $item) {

            $food = $item->food;

            // Check stock availability
            if ($food->stock < $item->quantity) {
                return back()->with('error', $food->name.' stock is not sufficient!');
            }

            // Reduce stock
            $food->stock -= $item->quantity;
            $food->save();

            // Insert Order Item
            OrderItems::create([
                'order_id' => $order->id,
                'food_id' => $item->food_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->total_price,
            ]);
        }

        // Clear user's cart
        Cart::where('employee_id', $employeeId)->delete();

        return redirect()->route('admin.user.order_list')->with('success', 'Order Placed Successfully!');
    }

    // public function employeeOrderList(Request $request)
    // {
    //     // Use employee_code from input field
    //     $employeeCode = $request->query('employee_code');

    //     $fromDate = $request->query('from_date');
    //     $toDate = $request->query('to_date');

    //     // Base query
    //     $query = Order::with(['employee']);

    //     // Filter by employee_code
    //     if ($employeeCode) {
    //         $query->where('employee_code', $employeeCode);
    //     }

    //     // Filter by date range
    //     if ($fromDate && $toDate) {
    //         $query->whereBetween('created_at', [$fromDate, $toDate]);
    //     }

    //     // Get all filtered orders
    //     $orders = $query->get();

    //     // Statistics
    //     $totalOrders = $orders->count();
    //     $paid = $orders->where('payment_status', 'paid')->count();
    //     $unpaid = $orders->where('payment_status', 'unpaid')->count();
    //     $totalAmount = $orders->sum('total_amount');

    //     $dueAmount = $orders->where('payment_status', 'unpaid')->sum('total_amount');
    //     $paidAmount = $orders->where('payment_status', 'paid')->sum('total_amount');

    //     return view('backend.pages.order.employee_order_list', compact(
    //         'orders',
    //         'employeeCode',
    //         'fromDate',
    //         'toDate',
    //         'totalOrders',
    //         'paid',
    //         'unpaid',
    //         'totalAmount',
    //         'dueAmount',
    //         'paidAmount'
    //     ));
    // }
    public function employeeOrderList(Request $request)
    {
        // Use employee_code from input field
        $employeeCode = $request->query('employee_code');

        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        // Base query
        // $query = Order::with(['employee']);
        $query = Order::with(['items.food.category', 'vendor', 'assistant', 'employee.team']);

        // Filter by employee_code
        if ($employeeCode) {
            $query->where('employee_code', $employeeCode);
        }

        // Filter by date range (from 00:00:00 to 23:59:59)
        if ($fromDate && $toDate) {

            $start = \Carbon\Carbon::parse($fromDate)->startOfDay();   // 00:00:00
            $end = \Carbon\Carbon::parse($toDate)->endOfDay();       // 23:59:59

            $query->whereBetween('created_at', [$start, $end]);
        }

        // Get all filtered orders
        $orders = $query->latest()->get();

        // Statistics
        $totalOrders = $orders->count();
        $paid = $orders->where('payment_status', 'paid')->count();
        $unpaid = $orders->where('payment_status', 'unpaid')->count();
        $totalAmount = $orders->sum('total_amount');

        $dueAmount = $orders->where('payment_status', 'unpaid')->sum('total_amount');
        $paidAmount = $orders->where('payment_status', 'paid')->sum('total_amount');

        return view('backend.pages.order.employee_order_list', compact(
            'orders',
            'employeeCode',
            'fromDate',
            'toDate',
            'totalOrders',
            'paid',
            'unpaid',
            'totalAmount',
            'dueAmount',
            'paidAmount'
        ));
    }

    public function employeedeliverylist()
    {
        // Fetch employee orders
        $orders = Order::with(['items.food.category', 'vendor', 'assistant', 'employee.team'])
            ->where('employee_id', auth()->id())
            ->where('order_status', 'delivered') // Only pending orders
            ->latest()
            ->get();

        // Mark related notifications as read
        auth()->user()->unreadNotifications
            ->whereIn('data.order_id', $orders->pluck('id'))
            ->markAsRead();

        // Pass data to the view
        return view('backend.pages.order.employee_delivery_list', ['orders' => $orders]);
    }

    public function vendorOrderList()
    {
        $vendorId = auth()->id();

        $orders = Order::with(['items.food.category', 'vendor', 'employee.team'])
            ->whereHas('items.food', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->get();

        // Mark notifications as read
        auth()->user()->unreadNotifications
            ->whereIn('data.order_id', $orders->pluck('id'))
            ->markAsRead();

        return view('backend.pages.order.vendor.order_list', compact('orders'));
    }


    public function vendorOrderDliveyList()
    {
        $vendorId = auth()->id();

        $orders = Order::with(['items.food.category', 'vendor', 'employee.team'])
            ->where('order_status', 'delivered')
            ->whereHas('items.food', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->get();

        // Mark notifications as read
        auth()->user()->unreadNotifications
            ->whereIn('data.order_id', $orders->pluck('id'))
            ->markAsRead();

        return view('backend.pages.order.vendor.order_delivery_list', compact('orders'));
    }


    public function vendorOrderCancelList()
    {
        $vendorId = auth()->id();

        $orders = Order::with(['items.food.category', 'vendor', 'employee.team'])
            ->where('order_status', 'cancelled')
            ->whereHas('items.food', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->get();

        // Mark notifications as read
        auth()->user()->unreadNotifications
            ->whereIn('data.order_id', $orders->pluck('id'))
            ->markAsRead();

        return view('backend.pages.order.vendor.order_cancelled_list', compact('orders'));
    }


    public function employeecancellist()
    {
        // Fetch employee orders
        $orders = Order::with(['items.food.category', 'vendor', 'assistant', 'employee.team'])
            ->where('employee_id', auth()->id())
            ->where('order_status', 'cancelled') // Only pending orders
            ->latest()
            ->get();

        // Mark related notifications as read
        auth()->user()->unreadNotifications
            ->whereIn('data.order_id', $orders->pluck('id'))
            ->markAsRead();

        // Pass data to the view
        return view('backend.pages.order.employee_cancel_list', ['orders' => $orders]);
    }

    public function status(Request $request, $id)
    {
        // Log::info('here');
        $status = strtolower($request->order_status);

        $order = Order::with('items.food')->findOrFail($id);

        switch ($status) {

            case 'pending':
                if ($order->order_status === 'cancelled') {
                    return redirect()->back()->with('error', 'Can not change order is cancelled.');
                }

                $order->order_status = 'pending';
                $order->save();

                return redirect()->back()->with('success', 'Order status updated to Pending.');

            case 'delivered':
                if ($order->order_status === 'cancelled') {
                    return redirect()->back()->with('error', 'Can not change order is cancelled.');
                }
                $order->order_status = 'delivered';
                $order->save();

                return redirect()->back()->with('success', 'Order status updated to Delivered.');

            case 'cancel':
            case 'cancelled':

                if ($order->order_status === 'cancelled') {
                    return redirect()->back()->with('error', 'Order is already cancelled.');
                }

                foreach ($order->items as $item) {
                    $food = $item->food;
                    $food->stock += $item->quantity;
                    $food->save();
                }

                $order->order_status = 'cancelled';
                $order->save();

                return redirect()->back()->with('success', 'Order cancelled and stock restored.');

            case 'paid':
                if ($order->order_status === 'cancelled') {
                    return redirect()->back()->with('error', 'Can not change order is cancelled.');
                }
                $order->payment_status = 'paid';
                $order->save();

                return redirect()->back()->with('success', 'Payment status updated to Paid.');

            case 'unpaid':
                if ($order->order_status === 'cancelled') {
                    return redirect()->back()->with('error', 'Can not change order is cancelled.');
                }
                $order->payment_status = 'unpaid';
                $order->save();

                return redirect()->back()->with('success', 'Payment status updated to Unpaid.');

            case 'cod':
                if ($order->order_status === 'cancelled') {
                    return redirect()->back()->with('error', 'Can not change order is cancelled.');
                }
                $order->payment_type = 'cod';
                $order->save();

                return redirect()->back()->with('success', 'Payment type updated to COD.');

            case 'due':
                if ($order->order_status === 'cancelled') {
                    return redirect()->back()->with('error', 'Can not change order is cancelled.');
                }
                $order->payment_type = 'due';
                $order->save();

                return redirect()->back()->with('success', 'Payment type updated to Due.');
        }

        return redirect()->back()->with('error', 'Invalid status value.');
    }

    public function pending($id)
    {
        // Find the order
        $order = Order::findOrFail($id);

        // Update the status to Pending
        $order->order_status = 'Pending';
        $order->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Order status updated to Pending.');
    }

    public function delivered($id)
    {
        // Find the order
        $order = Order::findOrFail($id);

        // Update the status to Pending
        $order->order_status = 'delivered';
        $order->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Order status updated to delivered.');
    }

    public function cancel($id)
    {
        // Find the order with items and related food
        $order = Order::with('items.food')->findOrFail($id);

        // Only cancel if not already canceled
        if ($order->order_status === 'cancelled') {
            return redirect()->back()->with('error', 'Order is already canceled.');
        }

        // Restore stock for each food item
        foreach ($order->items as $item) {
            $food = $item->food;
            $food->stock += $item->quantity;
            $food->save();
        }

        // Update order status to cancelled
        $order->order_status = 'cancelled';
        $order->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Order cancelled and stock restored.');
    }

    public function paid($id)
    {
        // Find the order
        $order = Order::findOrFail($id);

        // Update the status to Pending
        $order->payment_status = 'paid';
        $order->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Order status updated to paid.');
    }

    public function unpaid($id)
    {
        // Find the order
        $order = Order::findOrFail($id);

        // Update the status to Pending
        $order->payment_status = 'unpaid';
        $order->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Order status updated to unpaid.');
    }

    public function cod($id)
    {
        // Find the order
        $order = Order::findOrFail($id);

        // Update the status to Pending
        $order->payment_type = 'cod';
        $order->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Order status updated to cod.');
    }

    public function due($id)
    {
        // Find the order
        $order = Order::findOrFail($id);

        // Update the status to Pending
        $order->payment_type = 'due';
        $order->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Order status updated to due.');
    }

    public function order_list()
    {
        $orders = Order::with(['items.food.category', 'vendor', 'assistant', 'employee.team'])
            ->where('employee_id', Auth::id())->latest()->get();

        return view('backend.pages.user.order', compact('orders'));
    }

    public function order_cancel($id)
    {

        $order = Order::with('items.food')
            ->where('employee_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Only cancel if not already cancelled
        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'Order is already cancelled.');
        }

        // Restore stock for each item
        foreach ($order->items as $item) {
            $food = $item->food;
            $food->stock += $item->quantity; // Add quantity back to stock
            $food->save();
        }
        // public function order_cancelled()
        // {
        //     $orders = Order::with(['items.food.category', 'vendor', 'assistant', 'employee.team'])
        //                     ->where('employee_id', Auth::id())->where('order_status', 'cancelled')->latest()->get();
        //     return view('backend.pages.user.cancel', compact('orders'));
        // }

        // Update order status to cancelled
        $order->order_status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order cancelled and stock restored.');
    }
}
