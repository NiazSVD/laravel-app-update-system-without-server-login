<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->get();

        $roles = Role::all();

        $defaultRole = 'admin';

        return view('backend.pages.users.index', compact('users', 'roles', 'defaultRole'));
    }


    public function employee()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'employee');
        })->get();

        $roles = Role::all();

        $teams = Team::all();

        $defaultRole = 'employee';

        return view('backend.pages.users.employee', compact('users', 'roles', 'defaultRole', 'teams'));
    }


    public function vendor()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'vendor');
        })->get();

        $roles = Role::all();

        $defaultRole = 'vendor';

        return view('backend.pages.users.vendor', compact('users', 'roles', 'defaultRole'));
    }


    public function delivery()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'delivery');
        })->get();

        $roles = Role::all();

        $defaultRole = 'delivery';

        return view('backend.pages.users.delivery', compact('users', 'roles', 'defaultRole'));
    }

    public function other()
    {
        $excludedRoles = ['admin', 'employee', 'vendor', 'delivery'];

        $users = User::whereHas('roles', function ($q) use ($excludedRoles) {
            $q->whereNotIn('name', $excludedRoles);
        })->get();

        $roles = Role::whereNotIn('name', $excludedRoles)->get();

        $defaultRole = null;

        return view('backend.pages.users.other', compact('users', 'roles', 'defaultRole'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'phone'       => 'required|string|unique:users,phone',
            'password'    => 'required|string|min:6|confirmed',
            'role'        => 'required',
            'team_id'     => 'nullable|exists:teams,id',
            'employee_code' => 'nullable|string|max:255',
            'floor'       => 'nullable|integer',
            'row'         => 'nullable|integer',
            'seat_number' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', 'Validation failed! Please check the form.')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name'           => $request->name,
            'address'        => $request->address,
            'phone'          => $request->phone,
            'password'       => Hash::make($request->password),

            'team_id'        => $request->team_id,
            'employee_code'  => $request->employee_code,

            'floor'          => $request->floor ?? 0,
            'row'            => $request->row ?? 0,
            'seat_number'    => $request->seat_number ?? 0,
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User created successfully!');
    }



    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'phone'       => 'required|string|unique:users,phone,' . $user->id,
            'role'        => 'required|exists:roles,id',
            'team_id'        => 'nullable|exists:teams,id',
            'employee_code'  => 'nullable|string|max:255',
            'floor'       => 'nullable|integer|required_with:row,seat_number',
            'row'         => 'nullable|integer|required_with:floor,seat_number',
            'seat_number' => 'nullable|integer|required_with:floor,row',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', 'Validation failed! Please check the form.')
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name'          => $request->name,
            'address'       => $request->address,
            'phone'         => $request->phone,
            'team_id'        => $request->team_id,
            'employee_code'  => $request->employee_code,
            'floor'         => $request->floor ?? 0,
            'row'           => $request->row ?? 0,
            'seat_number'   => $request->seat_number ?? 0,
        ]);

        $role = Role::findById($request->role);
        $user->syncRoles([$role]);

        return redirect()->back()->with('success', 'User updated successfully!');
    }




    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}
