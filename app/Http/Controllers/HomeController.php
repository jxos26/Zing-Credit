<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        // return view('users.summary',compact('title'));
        return redirect('/zing-credit');
    }

    public function profile()
    {
        return view('users.profile');
    }

    public function updateProfile(Request $request)
    {
        
        $paths = "";
        if($request->hasFile('img')){
            $file = $request->file('img');
            $name = $file->getcompanyOriginalName(); 
            $file->move('images/profile-pic/', $name);

            $paths = "images/profile-pic/".$name;
        }

        if($paths == "" && $request->password == ""){
            $query =DB::connection('mysql')->table('users')->where('id', $this->userLogged()['id'])
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'lastname'          => ucwords($request->lastname),                
                'email'             => $request->email,
            ]);
        }elseif($paths != "" && $request->password == ""){
            $query =DB::connection('mysql')->table('users')->where('id', $this->userLogged()['id'])
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

            $query =DB::connection('mysql')->table('users')->where('id', $this->userLogged()['id'])
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

            $query =DB::connection('mysql')->table('users')->where('id', $this->userLogged()['id'])
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'lastname'          => ucwords($request->lastname),
                'email'             => $request->email,
                'password'          => Hash::make($request->password)
            ]);
        }   
        
        if($query){            
            return redirect('/profile')->with('status','Profile has been updated!');
        }else{
            return redirect('/profile')->with('error','Profile failed to update!');
        }

    }

    public function zingCredit()
    {

        $user = $this->userLogged();

        $dt = date('Y-m-d', strtotime('today - 30 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=30; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d)); 
            if ($user->type == "ADMIN"){
                $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
            }else{
                $records = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.$d.'%')->count();
            }   
        
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);
        
        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->get();
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $leads = DB::connection('mysql')->table('leads')->where('company',$user->company)->get();
            $company  = "";
        }
        
       
        $company_title = "";
        $title = "ZING CREDIT";
        $leads_filter = "Last 30 Days";
        return view('users.zing-credit', compact('title','labels','datas','title','leads','leads_filter','company','company_title'));
    }

    public function zingCreditToday()
    {
            
        $user = $this->userLogged();
    
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
            if ($user->type == "ADMIN"){
                $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
            }else{
                $records = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
            }
        
            $data[] = $records;

            $i+=1;
        }     

        $labels = json_encode($label);
        $datas = json_encode($data);
        
        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d').'%')->get(); 
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $leads = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.date('Y-m-d').'%')->get(); 
            $company = "";
        }

        $company_title = "";
        
        $title = "ZING CREDIT";
        $leads_filter = "Today";
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title'));        
    }

    public function zingCreditYesterday()
    {
            
        $user = $this->userLogged();
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
            if ($user->type == "ADMIN"){
                $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
            }else{
                $records = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
            }
            
            $data[] = $records;

            $i+=1;
        }     

        $labels = json_encode($label);
        $datas = json_encode($data);
        
        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$rd.'%')->get();
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $leads = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.$rd.'%')->get();
            $company = "";
        }
        
        $company_title = "";
        $title = "ZING CREDIT";
        $leads_filter = "Yesterday";
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title'));
    }

    public function zingCreditLast7Days()
    {
        $user = $this->userLogged();
        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->get();
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $leads = DB::connection('mysql')->table('leads')->where('company',$user->company)->get();
            $company = "";
        }
        

        $dt = date('Y-m-d', strtotime('today - 7 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=7; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));  
            if ($user->type == "ADMIN"){
                $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
            }else{
                $records = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.$d.'%')->count();
            }   
            
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        $company_title = "";

        $title = "ZING CREDIT";
        $leads_filter = "Last 7 Days";
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title'));
    }

    public function zingCreditDateRange(Request $request)
    {
        $user = $this->userLogged();
        
        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->get();
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $leads = DB::connection('mysql')->table('leads')->where('company',$user->company)->get();
            $company = "";
        }

        $date1 = date('Y-m-d', strtotime($request->start_date));
        $date2 = date('Y-m-d', strtotime($request->end_date));
        $label = array();
        $data = array();
        while (strtotime($date1) <= strtotime($date2)) {

            $label[] = date('F d',strtotime($date1));    
            if ($user->type == "ADMIN"){
                $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$date1.'%')->count();
            }else{
                $records = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.$date1.'%')->count();
            }
            
            $data[] = $records;
            $date1 = date ("Y-m-d", strtotime("+1 days", strtotime($date1)));
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        $company_title = "";

        $title = "ZING CREDIT";
        $leads_filter = 'FROM : '.date('F d Y', strtotime($request->start_date)) .' TO : '.date('F d Y', strtotime($request->end_date));
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title'));
    }

    public function zingCreditCompany($company)
    {
        
        $dt = date('Y-m-d', strtotime('today - 30 days'));
        
        $leads_filter = $company." Leads for the last 30 Days";
        $company_title = $company;

        $label = array();
        $data = array();
        for ($i=1;$i <=30; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));     
            $records = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.$d.'%')->count();
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        $leads = DB::connection('mysql')->table('leads')->where('company',$company)->get();       

        $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();

        $title = "ZING CREDIT";        
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title'));
    }


    public function getUsers()
    {
        // $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        $user = $this->userLogged();
        if ($user->type != "ADMIN"){
            return redirect('/zing-credit');
        }

        $users = DB::connection('mysql')->table('users')->get();
        $title = "USERS LIST" ;
        return view('users.users-list', compact('title','users'));
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
            return redirect('/users')->with('status','User '.$name.' has been created!');
        }else{
            return redirect('/users')->with('error','Error Creating User!');
        }  
    }

    function email_password($email,$type,$password,$subject,$name){

        $data = array( 'email' => $email, 'subject' => $subject );
        Mail::send('users.email-password', array(            
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
        
        //dd($getcompany);
        
        return response()->json(['success' => true, 'company' => $getcompany ]);
    }

}
