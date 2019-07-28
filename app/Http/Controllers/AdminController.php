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
        // $status = "";
        // $title = "SUMMARY";
        // return view('admins.summary',compact('title'));
        return redirect('/admin/zing-credit');
    }

    public function profile()
    {
        return view('admins.profile');
    }

    public function updateProfile(Request $request)
    {
        
        dd($this->userLogged()['id']);
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
        $leads_count = DB::connection('mysql')->table('leads')->count();
        $amount = 0;
        foreach($leads as $l){
            $amount = $amount + $l->amount;
        }

        $dt = date('Y-m-d', strtotime('today - 30 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=30; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));     
            $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        $clients = DB::connection('mysql')->table('leads')->groupBy('clients')->get();

        $title = "ZING CREDIT";
        $leads_filter = "LAST 30 DAYS";
        return view('admins.zing-credit', compact('title','leads','labels','datas','leads_filter','clients'));
    }
    public function zingCreditToday()
    {
            

        $label = array();
        $data = array();
        
        $rd = date('Y-m-d');
        $i=0;
        while($i <= 23){

            $l = strlen($i);            
            if($l == 1 ){
                $d =  date('Y-m-d') .' 0'.$i.':00'; 
            }else{
                $d =  date('Y-m-d') .' '.$i.':00'; 
            }           
            
            $label[] = date('h A',strtotime($d));
            $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
            $data[] = $records;

            $i+=1;
        }     

        $labels = json_encode($label);
        $datas = json_encode($data);
        
        $clients = DB::connection('mysql')->table('leads')->groupBy('clients')->get();
        
        $leads = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d').'%')->get(); 
        $title = "ZING CREDIT";
        $leads_filter = "TODAY";
        return view('admins.zing-credit', compact('title','leads','labels','datas','leads_filter','clients'));
    }

    public function zingCreditYesterday()
    {
            

        $label = array();
        $data = array();
        
        $rd = date('Y-m-d', strtotime('today - 1 days'));        
        $i=0;
        while($i <= 23){

            $l = strlen($i);            
            if($l == 1 ){
                $d =  $rd  .' 0'.$i.':00'; 
            }else{
                $d =  $rd  .' '.$i.':00'; 
            }           
            
            $label[] = date('h A',strtotime($d));
            $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
            $data[] = $records;

            $i+=1;
        }     

        $labels = json_encode($label);
        $datas = json_encode($data);
                
        $clients = DB::connection('mysql')->table('leads')->groupBy('clients')->get();
        
        $leads = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$rd.'%')->get(); 
        $title = "YESTERDAY";
        $leads_filter = "Last 30 Days";
        return view('admins.zing-credit', compact('title','leads','labels','datas','leads_filter','clients'));
    }

    public function zingCreditLast7Days()
    {
        $leads = DB::connection('mysql')->table('leads')->get();
        // $leads_count = DB::connection('mysql')->table('leads')->count();
        // $amount = 0;
        // foreach($leads as $l){
        //     $amount = $amount + $l->amount;
        // }

        $dt = date('Y-m-d', strtotime('today - 7 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=7; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));     
            $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        $clients = DB::connection('mysql')->table('leads')->groupBy('clients')->get();

        $title = "ZING CREDIT";
        $leads_filter = "LAST 7 DAYS";
        return view('admins.zing-credit', compact('title','leads','labels','datas','leads_filter','clients'));
    }

    public function zingCreditDateRange(Request $request){

        $leads = DB::connection('mysql')->table('leads')->get();

        $date1 = date('Y-m-d', strtotime($request->start_date));
        $date2 = date('Y-m-d', strtotime($request->end_date));
        $label = array();
        $data = array();
        while (strtotime($date1) <= strtotime($date2)) {

            $label[] = date('F d',strtotime($date1));     
            $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$date1.'%')->count();
            $data[] = $records;
            $date1 = date ("Y-m-d", strtotime("+1 days", strtotime($date1)));
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        $clients = DB::connection('mysql')->table('leads')->groupBy('clients')->get();

        $title = "ZING CREDIT";
        $leads_filter = 'FROM : '.date('F d Y', strtotime($request->start_date)) .' TO : '.date('F d Y', strtotime($request->end_date));
        return view('admins.zing-credit', compact('title','leads','labels','datas','leads_filter','clients'));
    }

    public function zingCreditClient($client)
    {
        
        // $leads_count = DB::connection('mysql')->table('leads')->count();
        // $amount = 0;
        // foreach($leads as $l){
        //     $amount = $amount + $l->amount;
        // }

        $dt = date('Y-m-d', strtotime('today - 30 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=30; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));     
            $records = DB::connection('mysql')->table('leads')->where('clients',$client)->where('created_at','LIKE','%'.$d.'%')->count();
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        $leads = DB::connection('mysql')->table('leads')->where('clients',$client)->get();
        $clients = DB::connection('mysql')->table('leads')->groupBy('clients')->get();

        $title = "ZING CREDIT";
        $leads_filter = $client." Leads for last 30 Days";
        return view('admins.zing-credit', compact('title','leads','labels','datas','leads_filter','clients'));
    }

    public function getUsers()
    {
        // $clients = DB::connection('mysql')->table('leads')->groupBy('clients')->get();
        $users = DB::connection('mysql')->table('users')->get();
        $title = "USERS LIST" ;
        return view('users', compact('title','users'));
    }

    public function userRegister(Request $request)
    {

        if($request->option == "send"){
            $password = generateRandomString(12);            
            $this->email_password($request->email,'USER CREATED',$password,'OptiAuto has invited you to join OptiAuto Reporting Lead Tracking!',$request->firstname);
        }else{
            $password = $request->password;
        }
        $query =DB::connection('mysql')->table('users')->insert([  
            'firstname'     => $request->firstname,
            'middlename'    => $request->middlename,
            'lastname'      => $request->lastname,
            'email'         => $request->email,
            'password'      => Hash::make($password),
            'img'           => 'images/users.png', 
            'status'        => "ACTIVE",
            'type'          => "USER",
            'created_at'  => date('Y-m-d H:i:s')
        ]);
        $name = $request->firstname .' '. $request->lastname;
        
        if($query)
        {                       
            return redirect('/admin/users')->with('status','User '.$name.' has been created!');
        }else{
            return redirect('/admin/users')->with('error','Error Creating User!');
        } 

    }

    function email_password($email,$type,$password,$subject,$name){

        $data = array( 'email' => $email, 'subject' => $subject );
        Mail::send('admins.email-password', array(            
            'type'      => $type,
            'email'     => $email,
            'password'  => $password,
            'name'      => $name
        ), function($message) use ($data)
        {
            $message->to($data['email'])->subject($data['subject']);
        });
    }

    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function disabledUser($id)
    {

        $user = DB::connection('mysql')->table('users')->where('id',$id)->first();

        $name = $user->firstname .' '. $user->lastname;

        $query =DB::connection('mysql')->table('users')->where('id', $id)
        ->update([                
            'status'         => "DISABLED"
        ]);

        if($query)
        {                       
            return redirect('/admin/users')->with('status','Client '.$name.' has been disabled!');
        }else{
            return redirect('/admin/users')->with('error','Error Disabled Request!');
        } 

    }

    public function updateUserSettings(Request $request){
        
        if($request->password == "" or $request->password == null){
            $query =DB::connection('mysql')->table('users')->where('id', $request->user_id)
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'middlename'        => ucwords($request->middlename), 
                'lastname'          => ucwords($request->lastname),                
                'email'             => $request->email,
                'company'           => $request->company,
                'status'            => $request->status,
            ]);
        }else{
            $query =DB::connection('mysql')->table('users')->where('id', $request->user_id)
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'middlename'        => ucwords($request->middlename), 
                'lastname'          => ucwords($request->lastname),                
                'email'             => $request->email,
                'company'            => $request->company,
                'status'            => $request->status,
                'password'          => Hash::make($request->password),
            ]);
        }

        if($query)
        {                       
            return redirect('/users')->with('status','User '.$request->firstname.' has been updated!');
        }else{
            return redirect('/users')->with('error','Error Updating User!');
        } 
    }


    public function getCompany(){

        $getcompany= array();
        $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();   
        
        foreach($company as $l){
            $getcompany[] = ['company' => $l->company];
        }
        
        //dd($getclients);
        
        return response()->json(['success' => true, 'company' => $getcompany ]);
    }

}