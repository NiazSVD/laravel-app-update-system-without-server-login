<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $data = [];

        $data['total_user'] = User::count();
        $data['total_vendor'] = User::role('vendor')->count();
        $data['total_employee'] = User::role('employee')->count();
        $data['total_food_post'] = Food::count();


        $data['my_food_post'] = Food::where('vendor_id', Auth::id())
            ->count();

        $data['pending_order'] = Order::where('vendor_id', Auth::id())
            ->where('order_status', 'pending')
            ->count();

        $data['delivered_order'] = Order::where('vendor_id', Auth::id())
            ->where('order_status', 'delivered')
            ->count();

        $data['cancelled_order'] = Order::where('vendor_id', Auth::id())
            ->where('order_status', 'cancelled')
            ->count();

        return view('backend.pages.dashboard', compact('data'));
    }
}
