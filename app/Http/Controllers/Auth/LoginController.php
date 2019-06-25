<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/summary';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function showLoginForm()
    {
        return redirect('/');
    }

    public function login(Request $request)
    {
    
        // Validate the form data
        $this->validate($request, [
            'username'   => 'required|max:20',
            'password' => 'required|min:6'
        ]);
        

        if($this->userCheck($request->username) == 1)
        {            
            // Attempt to log the user in
            if (Auth::guard()->attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
                // if successful, then redirect to their intended location
                return redirect('/admin/summary');
            }else{
                return redirect()->back()->withInput($request->only('username', 'remember'))->with('error','Username and Password did not Match!');
            }
        }else{
            if($this->pendingCheck($request->username) == 1)
            {
                return redirect('/pending-account');
            }else{
            // if unsuccessful, then redirect back to the login with the form data
                return redirect()->back()->withInput($request->only('username', 'remember'))->with('error','Failed to login!');
            }
        }
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }

    function userCheck($username)
    {
      $user = DB::connection('mysql')->table('users')->where('username',$username)->where('status','ACTIVE')->count();
      return $user;
    }

    function pendingCheck($username)
    {
      $user = DB::connection('mysql')->table('users')->where('username',$username)->where('status','PENDING')->count();
      return $user;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect('/')->with('error','User does not exist!');
    }

}