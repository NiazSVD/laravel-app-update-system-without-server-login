<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile()
    {
        return view('backend.pages.profile.index');
    }

    public function Update_profile()
    {
        return 'OK';
    }

    public function password()
    {
        return view('backend.pages.profile.password');
    }

    public function Update_password()
    {
        return 'OK';
    }
}
