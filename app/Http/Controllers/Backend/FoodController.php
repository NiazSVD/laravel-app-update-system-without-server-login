<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class FoodController extends Controller
{
    public function index(): View
    {
        $get_foods = Food::latest()->get();
        Log::info($get_foods);
        $get_categories = Category::all();

        return view('backend.pages.food.food', compact('get_foods', 'get_categories'));
    }

    public function vendorindex(): View
    {
        $vendorId = auth()->id(); // logged-in vendor ID

        // Get only foods created by this vendor
        $get_foods = Food::where('vendor_id', $vendorId)
            ->orderBy('id', 'DESC') // optional: newest first
            ->get();

        $get_categories = Category::all();

        return view('backend.pages.food.vendor', compact('get_foods', 'get_categories'));
    }


    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->storeAs('uploads/food', $imageName, 'public');
        }

        Food::create([
            'vendor_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Food item added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        try {
            $food = Food::findOrFail($id);

            $food->name = $request->name;
            $food->category_id = $request->category_id;
            $food->price = $request->price;
            $food->stock = $request->stock;
            $food->description = $request->description;
            $food->status = $request->status;

            if ($request->has('remove_image') && $request->remove_image == 1) {
                if ($food->image) {
                    $imagePath = storage_path('app/public/uploads/food/' . $food->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $food->image = null;
            } elseif ($request->hasFile('image')) {
                if ($food->image) {
                    $oldImagePath = storage_path('app/public/uploads/food/' . $food->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $image->move(storage_path('app/public/uploads/food'), $imageName);
                $food->image = $imageName;
            }

            $food->save();

            return redirect()->back()->with('success', 'Food item updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating food item: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $food = Food::findOrFail($id);

            if ($food->image) {
                $imageName = $food->image;

                $possiblePaths = storage_path('app/public/uploads/food/' . $imageName);
                if (file_exists($possiblePaths)) {
                    unlink($possiblePaths);
                }
            }

            $food->delete();

            return redirect()->back()->with('success', 'Food item deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting food: ' . $e->getMessage());
        }
    }



    public function foodListViewAdmin()
    {

        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now('Asia/Dhaka')->format('H:i:s');

        $categories = Category::where('status', 1)
            ->whereTime('start_time', '<=', $currentTime)
            ->whereTime('end_time', '>=', $currentTime)
            ->with(['foods' => function($q) use ($today) {
                $q->where('status', 1)
                ->whereDate('meal_date', $today);
            }])
            ->get();

            $defaultCategoryId = $categories->count() > 0 ? $categories->first()->id : 0;

        return view('backend.pages.food.admin_food', compact('categories', 'defaultCategoryId'));
    }



    public function foodListView()
    {
        $userId = Auth::id();

        $data['foods'] = Food::where('status', 1)
            ->where('vendor_id', $userId)
            ->get();

        return view('backend.pages.food.food_list', $data);
    }


    public function foodListViewVendor()
    {
        $userId = auth()->id();

        // Load only categories that have foods of this vendor
        $categories = Category::whereHas('foods', function ($q) use ($userId) {
            $q->where('vendor_id', $userId)->where('status', 1);
        })
            ->with(['foods' => function ($q) use ($userId) {
                $q->where('vendor_id', $userId)->where('status', 1);
            }])
            ->get();

        return view('backend.pages.food.food', compact('categories'));
    }

    public function vendorindexfood()
    {
        $userId = auth()->id();
        $today  = Carbon::today()->format('Y-m-d');

        $categories = Category::whereHas('foods', function ($q) use ($userId, $today) {
                $q->where('vendor_id', $userId)
                ->where('status', 1)
                ->whereDate('meal_date', $today);   // only today's food
            })
            ->with(['foods' => function ($q) use ($userId, $today) {
                $q->where('vendor_id', $userId)
                ->where('status', 1)
                ->whereDate('meal_date', $today);   // eager load only today food
            }])
            ->get();

        return view('backend.pages.food.vendor_food', compact('categories'));
    }

    //category wise food show by ajax
    public function getFoodsByCategory($categoryId)
    {
        $vendorId = auth()->id();
        $today    = Carbon::today()->format('Y-m-d');

        $foods = Food::select('id', 'name', 'meal_date')
            ->where('category_id', $categoryId)
            ->where('vendor_id', $vendorId)
            ->get()
            ->map(function ($food) use ($today) {

                return [
                    'id'        => $food->id,
                    'name'      => $food->name,
                    'checked'  => $food->meal_date == $today ? true : false
                ];
            });

        return response()->json($foods);
    }



    /// today food menu store

    public function todayFoodstore(Request $request)
    {
        $request->validate([
            'food_ids'    => 'array',
            'category_id' => 'required|integer'
        ]);

        $vendorId = auth()->id();
        $categoryId = $request->category_id;
        $today = Carbon::today()->format('Y-m-d');

        // Uncheck করা food গুলো current category তে selected না → null
        Food::where('vendor_id', $vendorId)
            ->where('category_id', $categoryId)
            ->whereNotIn('id', $request->food_ids ?? [])
            ->update(['meal_date' => null]);

        // Checked food গুলো update today date
        if(!empty($request->food_ids)){
            Food::whereIn('id', $request->food_ids)
                ->where('vendor_id', $vendorId)
                ->where('category_id', $categoryId)
                ->update(['meal_date' => $today]);
        }

        return back()->with('success', 'আজকের খাবার আপডেট হয়েছে');
    }



}
