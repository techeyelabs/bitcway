<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use App\Mail\Common;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('user.auth.login');
    }

    public function loginAction(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'is_email_verified' => true, 'status' => true], $request->remember)) {
            return redirect()->intended(route('user-dashboard'));
        }

        return redirect()->back()->with('error_message', 'Wrong credentials..');
    }

    public function signup(Request $request)
    {
        return view('user.auth.signup');
    }

    public function signupAction(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'furigana' => 'required',
            'username' => 'required|min:6|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'agree' => 'required'
        ]);

        $User = new User();
        $User->first_name = $request->first_name;
        $User->last_name = $request->last_name;
        $User->furigana = $request->furigana;
        $User->username = $request->username;
        $User->email = $request->email;
        $User->password = Hash::make($request->password);
        $User->verification_token = time().rand(1000,9999);
        $User->save();

        $User->view = 'emails.verify_email';
        $User->subject = 'Verify email';
        Mail::to($User->email)
            ->send(new Common($User));

        return redirect()->back()->with('success_message', 'Registration successfull,please check your email to activate your account.');
    }

    public function verify(Request $request)
    {
        $check = User::where('verification_token', $request->token)->first();
        if($check){
            $check->is_email_verified = true;
            $check->status = true;
            $check->verification_token = null;
            $check->save();
            return redirect()->route('login')->with('success_message', 'Your account is active now.'); 
        }
        return redirect()->route('login')->with('error_message', 'Invalid token.');
    }

    public function changePassword(Request $request)
    {
        return view('user.auth.change_password');

    }

    public function changePasswordAction(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);
        $User = User::find(Auth::user()->id);
        if(Hash::check($request->old_password, $User->password)){
            $User->password = Hash::make($request->password);
            $User->save();
            return redirect()->back()->with('success_message', 'Password has been changed!!');
        }
        return redirect()->back()->with('error_message', 'Old password does not match!!');

    }

    public function forgot(Request $request)
    {
        return view('user.auth.forgot');       

    }
    public function forgotAction(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);
        $User = User::where('email', $request->email)->first();
        if($User){
            $User->verification_token = time().rand(1000,9999);
            $User->save();
            $User->view = 'emails.reset_password';
            $User->subject = 'Reset password';
            Mail::to($User->email)
                ->send(new Common($User));
            return redirect()->back()->with('success_message', 'Please check your email for furthur action!!');
        }
        return redirect()->back()->with('error_message', 'Invalid email,please contact administrator!!');
    }

    public function reset(Request $request)
    {
        $User = User::where('verification_code', $request->token);
        if($User) return view('user.auth.reset');
        return redirect()->route('user-dashboard')->with('error_message', 'Invalid request,please contact administrator!!');

    }

    public function resetAction(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|confirmed'
        ]);
        $User = User::where('verification_token', $request->token)->first();
        if($User){
            $User->password = Hash::make($request->password);
            $User->verification_token = '';
            $User->save();
            return redirect()->back()->with('success_message', 'Password has been changed!!');
        }
        return redirect()->back()->with('error_message', 'Invalid request,please contact administrator!!');

    }

    public function updateProfile(Request $request)
    {
        return view('user.auth.update_profile');

    }

    public function updateProfileAction(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'furigana' => 'required'
        ]);
        $User = User::find(Auth::user()->id);
        $User->first_name = $request->first_name;
        $User->last_name = $request->last_name;
        $User->furigana = $request->furigana;
        $User->phone = $request->phone;
        $User->save();
        return redirect()->back()->with('success_message', 'Profile information updated!!');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
