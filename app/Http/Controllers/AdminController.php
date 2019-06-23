<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function userLogged(){
        $user = Auth::user();    
        return $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $status = "";
        $title = "SUMMARY";
        return view('admins.summary',compact('title'));
    }

    public function profile()
    {
        return view('admins.profile');
    }

    public function updateProfile(Request $request)
    {
        
        $paths = "";
        if($request->hasFile('img')){
            $file = $request->file('img');
            $name = $file->getClientOriginalName(); 
            $file->move('images/profile-pic/', $name);

            $paths = "images/profile-pic/".$name;
        }

        if($paths == "" && $request->password == ""){
            $query =DB::connection('mysql')->table('admins')->where('id', $this->userLogged()['id'])
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'lastname'          => ucwords($request->lastname),                
                'email'             => $request->email,
            ]);
        }elseif($paths != "" && $request->password == ""){
            $query =DB::connection('mysql')->table('admins')->where('id', $this->userLogged()['id'])
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'lastname'          => ucwords($request->lastname),
                'email'             => $request->email,
                'img'               => $paths
            ]);
        }elseif($paths != "" && $request->password != ""){
            // Validate the form data
            $this->validate($request, [
                'password' => 'required|min:6'
            ]);

            $query =DB::connection('mysql')->table('admins')->where('id', $this->userLogged()['id'])
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'lastname'          => ucwords($request->lastname),
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'img'               => $paths
            ]);
        }elseif($paths == "" && $request->password != ""){
            // Validate the form data
            $this->validate($request, [
                'password' => 'required|min:6'
            ]);

            $query =DB::connection('mysql')->table('admins')->where('id', $this->userLogged()['id'])
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'lastname'          => ucwords($request->lastname),
                'email'             => $request->email,
                'password'          => Hash::make($request->password)
            ]);
        }   
        
        if($query){            
            return redirect('/admin/profile')->with('status','Profile has been updated!');
        }else{
            return redirect('/admin/profile')->with('error','Profile failed to update!');
        }

    }

    public function zingCredit()
    {
        $title = "ZING CREDIT";
        return view('admins.zing-credit', compact('title'));
    }

}
