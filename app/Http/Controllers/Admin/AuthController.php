<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('admin.auth.login');
    }

    public function loginAction(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => true], $request->remember)) {
            return redirect()->intended(route('admin-dashboard'));
        }

        return redirect()->back()->with('error_message', 'Wrong credentials..');
    }

    public function changePassword(Request $request)
    {
        return view('admin.auth.change_password');

    }

    public function changePasswordAction(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);
        $Admin = Admin::find(Auth::guard('admin')->user()->id);
        if(Hash::check($request->old_password, $Admin->password)){
            $Admin->password = Hash::make($request->password);
            $Admin->save();
            return redirect()->back()->with('success_message', 'Password has been changed!!');
        }
        return redirect()->back()->with('error_message', 'Old password does not match!!');

    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin-login');
    }
}
