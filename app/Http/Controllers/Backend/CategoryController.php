<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('backend.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg',
            'status'      => 'nullable|boolean',
            'start_time'  => 'nullable',
            'end_time'    => 'nullable',
        ]);

        $slug = $request->slug ? $request->slug : Str::slug($request->name);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);

            $imagePath = 'uploads/categories/' . $imageName;
        }

        Category::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'image'       => $imagePath,
            'status'      => $request->status ?? 0,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg',
            'status'      => 'nullable|boolean',
            'start_time'  => 'nullable',
            'end_time'    => 'nullable',
        ]);

        $slug = $request->slug ? $request->slug : Str::slug($request->name);

        if ($request->hasFile('image')) {

            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);

            $category->image = 'uploads/categories/' . $imageName;
        }

        $category->name        = $request->name;
        $category->slug        = $slug;
        $category->description = $request->description;
        $category->status      = $request->status ?? 0;
        $category->start_time  = $request->start_time;
        $category->end_time    = $request->end_time;
        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully.');
    }

}
