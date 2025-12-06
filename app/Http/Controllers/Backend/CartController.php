<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Food;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('employee_id', Auth::id())->get();
        $grandTotal = $cartItems->sum('total_price');
        $profile_info = Auth::user();

        return view('backend.pages.cart.index', compact('cartItems', 'grandTotal', 'profile_info'));
    }


    public function addToCart(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $employeeId = auth()->user()->id;

        // REAL FOOD PRICE FROM DB
        $food = Food::findOrFail($request->food_id);

        $unitPrice = $food->price;
        $foodId = $food->id;

        // Check if item already exists in cart
        $cartItem = Cart::where('employee_id', $employeeId)
            ->where('food_id', $foodId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->total_price = $cartItem->quantity * $cartItem->unit_price;
            $cartItem->save();
        } else {
            $cartItem = Cart::create([
                'employee_id' => $employeeId,
                'food_id'     => $foodId,
                'quantity'    => $request->quantity,
                'unit_price'  => $unitPrice,
                'total_price' => $unitPrice * $request->quantity
            ]);
        }

        return response()->json([
            'status'     => 'success',
            'message'    => 'Added to Cart Successfully!',
            'cart_count' => Cart::where('employee_id', $employeeId)->sum('quantity')
        ]);
    }


    public function updateQty(Request $request, $id)
    {
        $item = Cart::find($id);

        if ($request->type == 'plus') {
            $item->quantity++;
        } else {
            if ($item->quantity > 1) {
                $item->quantity--;
            }
        }

        $item->total_price = $item->quantity * $item->unit_price;
        $item->save();

        return response()->json([
            'quantity' => $item->quantity,
            'total' => number_format($item->total_price, 2),
            'grand_total' => number_format(
                Cart::where('employee_id', auth()->id())->sum('total_price'),
                2
            )
        ]);
    }


    public function removeItem($id)
    {
        Cart::find($id)->delete();

        $remaining = Cart::where('employee_id', auth()->id())->sum('total_price');

        return response()->json([
            'grand_total' => number_format($remaining, 2),
            'empty' => $remaining == 0
        ]);
    }
}
