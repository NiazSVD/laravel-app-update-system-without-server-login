<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'team_id'       => ['required', 'integer', 'exists:teams,id'],
            'employee_code' => ['required', 'numeric', 'unique:users,employee_code'],
            'phone'         => ['required', 'string', 'unique:users,phone'],
            'floor'         => ['required', 'string'],
            'row'           => ['required', 'string'],
            'seat_number'   => ['required', 'string'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $user = User::create([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'team_id'       => $request->team_id,
            'employee_code' => $request->employee_code,
            'floor'         => $request->floor,
            'row'           => $request->row,
            'seat_number'   => $request->seat_number,
            'password'      => Hash::make($request->password),
        ]);

        event(new Registered($user));
        $user->assignRole('employee');

        Auth::login($user);

        return redirect(route('admin.dashboard', absolute: false));
    }
}
