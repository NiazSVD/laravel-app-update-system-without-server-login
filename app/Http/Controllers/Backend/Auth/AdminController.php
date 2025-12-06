<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('backend.auth.login');
    }
    public function list()
    {
        $data['admins'] = Admin::all();
        return view('backend.auth.index', $data);
    }

    public function create()
    {
        return view('backend.auth.register');
    }

    public function store(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
            'role_id'  => 'required',
            'status'   => 'required',
            'image'    => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Upload image
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $imagePath = 'uploads/admin/' . $imageName; // full path for DB
            $request->image->move(public_path('uploads/admin/'), $imageName);
        }

        Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => $request->role_id,
            'status'   => $request->status,
            'image'    => $imagePath, // save full path
        ]);

        Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => $request->role_id,
            'status'   => $request->status,
            'image'    => $imageName,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin Added Successfully!');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid Email or Password');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}