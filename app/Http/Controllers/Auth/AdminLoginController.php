<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function adminLoginForm()
    {
        return view('admins.login');
    }

    public function adminLogin(Request $request)
    {        
        // Validate the form data
        $this->validate($request, [
            'username'   => 'required|max:20',
            'password' => 'required|min:6'
        ]);

        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location
            return redirect('/admin/summary');        
        }

        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('username', 'remember'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
