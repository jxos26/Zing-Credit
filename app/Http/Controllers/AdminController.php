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
        $leads = DB::connection('mysql')->table('leads')->get();
        $title = "ZING CREDIT";
        return view('admins.zing-credit', compact('title','leads'));
    }

    public function getClients()
    {
        $users = DB::connection('mysql')->table('users')->get();
        $title = "CLIENT'S LIST" ;
        return view('admins.clients', compact('title','users'));
    }


    public function clientRegister(Request $request)
    {
        $query =DB::connection('mysql')->table('users')->insert([  
            'firstname'     => $request->firstname,
            'middlename'    => $request->middlename,
            'lastname'      => $request->lastname,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'img'           => 'images/users.png', 
            'status'        => "ACTIVE",
            'type'          => "USER",
            'created_at'  => date('Y-m-d H:i:s')
        ]);
        $name = $request->firstname .' '. $request->lastname;
        
        if($query)
        {                       
            return redirect('/admin/clients')->with('status','Client '.$name.' has been created!');
        }else{
            return redirect('/admin/clients')->with('error','Error Creating Client Request!');
        } 

    }

    public function disabledClient($id)
    {

        $user = DB::connection('mysql')->table('users')->where('id',$id)->first();

        $name = $user->firstname .' '. $user->lastname;

        $query =DB::connection('mysql')->table('users')->where('id', $id)
        ->update([                
            'status'         => "DISABLED"
        ]);

        if($query)
        {                       
            return redirect('/admin/clients')->with('status','Client '.$name.' has been disabled!');
        }else{
            return redirect('/admin/clients')->with('error','Error Disabled Request!');
        } 

    }

}
