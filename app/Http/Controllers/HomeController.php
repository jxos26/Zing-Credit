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
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $records = 0;                
                foreach($company as $l){
                    $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$d.'%')->count();
                    $records += $r;
                }  
            }   
        
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);
        
        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->get();
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            
            $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            $leads = array();
            foreach($company as $l){
                $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
            }         
        }
        
        $dr = "";
        $company_title = "";
        $title = "ZING CREDIT";
        $leads_filter = "Last 30 Days";
        return view('users.zing-credit', compact('title','labels','datas','title','leads','leads_filter','company','company_title','dr'));
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
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $records = 0;                
                foreach($company as $l){
                    $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                    $records += $r;
                } 
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
            $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            $leads = array();
            foreach($company as $l){
                $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->where('created_at','LIKE','%'.date('Y-m-d').'%')->get(); 
            } 
        }

        $company_title = "";
        $dr= "";
        $title = "ZING CREDIT";
        $leads_filter = "Today";
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));        
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
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $records = 0;                
                foreach($company as $l){
                    $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                    $records += $r;
                } 
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
            $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            $leads = array();
            foreach($company as $l){
                $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->where('created_at','LIKE','%'.$rd.'%')->get();
            } 
        }
        $dr = "";
        $company_title = "";
        $title = "ZING CREDIT";
        $leads_filter = "Yesterday";
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));
    }

    public function zingCreditLast7Days()
    {
        $user = $this->userLogged();
        

        $dt = date('Y-m-d', strtotime('today - 7 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=7; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));  
            if ($user->type == "ADMIN"){
                $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $records = 0;                
                foreach($company as $l){
                    $r = DB::connection('mysql')->table('leads')->where('company',$user->company)->where('created_at','LIKE','%'.$d.'%')->count();
                    $records += $r;
                } 
            }   
            
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->get();
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            $leads = array();
            foreach($company as $l){
                $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
            }
        }
        

        $company_title = "";
        $dr = '';
        $title = "ZING CREDIT";
        $leads_filter = "Last 7 Days";
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));
    }

    public function zingCreditDateRange($start,$end)
    {
        $user = $this->userLogged();        
        

        $date1 = date('Y-m-d', strtotime($start));
        $date2 = date('Y-m-d', strtotime($end));
        $dr = date('m/d/Y', strtotime($date1)) .' - '. date('m/d/Y', strtotime($date2));
        $label = array();
        $data = array();
        while (strtotime($date1) <= strtotime($date2)) {

            $label[] = date('F d',strtotime($date1));    
            if ($user->type == "ADMIN"){
                $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$date1.'%')->count();
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $records = 0;                
                foreach($company as $l){
                    $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$date1.'%')->count();
                    $records += $r;
                } 
            }
            
            $data[] = $records;
            $date1 = date ("Y-m-d", strtotime("+1 days", strtotime($date1)));
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        if ($user->type == "ADMIN"){
            $leads = DB::connection('mysql')->table('leads')->get();
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            $leads = array();
            foreach($company as $l){
                $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
            }
        }

        $company_title = "";
        
        $title = "ZING CREDIT";
        $leads_filter = 'FROM : '.date('F d Y', strtotime($start)) .' TO : '.date('F d Y', strtotime($end));
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));
    }

    public function zingCreditCompany($company)
    {
        $user = $this->userLogged();
    
        $dt = date('Y-m-d', strtotime('today - 30 days'));
        
        $leads_filter = $company. " Leads for the last 30 Days";
        $company_title = $company;

        $label = array();
        $data = array();
        for ($i=1;$i <=30; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));    
            if ($company == 'All Company') {                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();                    
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$d.'%')->count();
                        $records += $r;
                    }  
                }

            }else{ 
                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.$d.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$d.'%')->count();
                        $records += $r;
                    }  
                }
            }
            
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);


        if ($company == 'All Company') {           
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->get();  
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $leads = array();
                foreach($company as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
            }
        }else{                        
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('company',$company)->get();  
            }else{
                $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                $leads = array();
                foreach($usercompany as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
            }
        }

        if ($user->type == "ADMIN"){
            $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        }else{
            $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();     
        }


        $dr = "";
        $title = "ZING CREDIT";        
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));
    }
    
    public function zingCreditLast30DaysCompany($company)
    {

        $user = $this->userLogged();
                
        $company_title = $company;
        $leads_filter = "Last 30 Days";

        $dt = date('Y-m-d', strtotime('today - 30 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=30; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));         
            
            if ($company == 'All Company') {                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();                    
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$d.'%')->count();
                        $records += $r;
                    }  
                }

            }else{ 
                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.$d.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$d.'%')->count();
                        $records += $r;
                    }  
                }
            }

        
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);
        
        if ($company == 'All Company') {           
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->get();  
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $leads = array();
                foreach($company as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
            }
        }else{                        
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('company',$company)->get();  
            }else{
                $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                $leads = array();
                foreach($usercompany as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            }
        }
        
        $dr = "";
        $title = "ZING CREDIT";        
        return view('users.zing-credit', compact('title','labels','datas','title','leads','leads_filter','company','company_title','dr'));
    }


    public function zingCreditTodayCompany($company)
    {
            
        $user = $this->userLogged();


        $company_title = $company;
        $leads_filter = "Today";
    
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
            
            if ($company == 'All Company') {                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();                    
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                        $records += $r;
                    }  
                }

            }else{ 
                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                        $records += $r;
                    }  
                }
            }

        
            $data[] = $records;

            $i+=1;
        }     

        $labels = json_encode($label);
        $datas = json_encode($data);
        
        
        if ($company == 'All Company') {           
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d').'%')->get();
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $leads = array();
                foreach($company as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->where('created_at','LIKE','%'.date('Y-m-d').'%')->get();
                }  
            }
        }else{                        
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.date('Y-m-d').'%')->get(); 
            }else{
                $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                $leads = array();
                foreach($usercompany as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->where('created_at','LIKE','%'.date('Y-m-d').'%')->get();
                }  
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            }
        }
        
        $dr= "";
        $title = "ZING CREDIT";
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));        
    }

    public function zingCreditYesterdayCompany($company)
    {
            
        $user = $this->userLogged();
        $company_title = $company;
        $leads_filter = "Yesterday";

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
            
            if ($company == 'All Company') {                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();                    
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                        $records += $r;
                    }  
                }

            }else{ 
                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.date('Y-m-d H', strtotime($d)).'%')->count();
                        $records += $r;
                    }  
                }
            }
            
            $data[] = $records;

            $i+=1;
        }     

        $labels = json_encode($label);
        $datas = json_encode($data);
        
    
        if ($company == 'All Company') {           
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$rd.'%')->get();
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                $leads = array();
                foreach($company as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->where('created_at','LIKE','%'.$rd.'%')->get();
                }  
            }
        }else{                        
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.$rd.'%')->get();  
            }else{
                $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                $leads = array();
                foreach($usercompany as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->where('created_at','LIKE','%'.$rd.'%')->get();
                }  
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();  
            }
        }


        $dr = "";
        $title = "ZING CREDIT";        
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));
    }

    public function zingCreditLast7DaysCompany($company)
    {
        $user = $this->userLogged();
        $company_title = $company;
        $leads_filter = "Last 7 Days";

        

        $dt = date('Y-m-d', strtotime('today - 7 days'));

        $label = array();
        $data = array();
        for ($i=1;$i <=7; $i++){        
            $d = date('Y-m-d', strtotime($dt. '+'.$i.' day'));                   
            $label[] = date('M d',strtotime($d));  
            
            if ($company == 'All Company') {                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$d.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();                    
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$d.'%')->count();
                        $records += $r;
                    }  
                }

            }else{ 
                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.$d.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$d.'%')->count();
                        $records += $r;
                    }  
                }
            }
            
            $data[] = $records;
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

    
        if ($company == 'All Company') {           
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->get();  
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $leads = array();
                foreach($company as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
            }
        }else{                        
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('company',$company)->get();  
            }else{
                $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                $leads = array();
                foreach($usercompany as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();  
            }
        }

        
        $dr = '';
        $title = "ZING CREDIT";        
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));
    }

    public function zingCreditDateRangeCompany($company,$start,$end)
    {
        $user = $this->userLogged();

        $company_title = $company;
        $leads_filter = 'FROM : '.date('F d Y', strtotime($start)) .' TO : '.date('F d Y', strtotime($end));
        
        $date1 = date('Y-m-d', strtotime($start));
        $date2 = date('Y-m-d', strtotime($end));
        $dr = date('m/d/Y', strtotime($date1)) .' - '. date('m/d/Y', strtotime($date2));
        $label = array();
        $data = array();
        while (strtotime($date1) <= strtotime($date2)) {

            $label[] = date('F d',strtotime($date1));    
    
            if ($company == 'All Company') {                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('created_at','LIKE','%'.$date1.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();                    
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$date1.'%')->count();
                        $records += $r;
                    }  
                }

            }else{ 
                
                if ($user->type == "ADMIN"){
                    $records = DB::connection('mysql')->table('leads')->where('company',$company)->where('created_at','LIKE','%'.$date1.'%')->count();
                }else{
                    $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                    $records = 0;                
                    foreach($usercompany as $l){
                        $r = DB::connection('mysql')->table('leads')->where('company',$l->company)->where('created_at','LIKE','%'.$date1.'%')->count();
                        $records += $r;
                    }  
                }
            }

            
            $data[] = $records;
            $date1 = date ("Y-m-d", strtotime("+1 days", strtotime($date1)));
        }

        $labels = json_encode($label);
        $datas = json_encode($data);

        if ($company == 'All Company') {           
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->get();  
            }else{
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
                $leads = array();
                foreach($company as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
            }
        }else{                        
            if ($user->type == "ADMIN"){
                $leads = DB::connection('mysql')->table('leads')->where('company',$company)->get();  
            }else{
                $usercompany = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->where('company',$company)->get();
                $leads = array();
                foreach($usercompany as $l){
                    $leads[] = DB::connection('mysql')->table('leads')->where('company', $l->company)->get();
                }  
                $company = DB::connection('mysql')->table('users_companies')->where('user_id',$user->id)->get();
            }
        }

        
        $dr="";
        $title = "ZING CREDIT";
        
        return view('users.zing-credit', compact('title','leads','labels','datas','leads_filter','company','company_title','dr'));
    }

    public function getUsers()
    {
        // $company = DB::connection('mysql')->table('leads')->groupBy('company')->get();
        $user = $this->userLogged();
        if ($user->type != "ADMIN"){
            return redirect('/zing-credit');
        }

        $users = DB::connection('mysql')->table('users')->get();
        $user_company = DB::connection('mysql')->table('users_companies')->get();
        $title = "USERS LIST" ;
        return view('users.users-list', compact('title','users','user_company'));
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
                'status'            => $request->status,
            ]);
        }else{
            $query =DB::connection('mysql')->table('users')->where('id', $request->user_id)
            ->update([                
                'firstname'         => ucwords($request->firstname),
                'middlename'        => ucwords($request->middlename), 
                'lastname'          => ucwords($request->lastname),                
                'email'             => $request->email,
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


    public function getCompany(Request $request){

        
        $getcompany= array();

        $company = DB::connection('mysql')->table('leads')->groupBy('company')->get(); 
        foreach($company as $l){
            $cnt = 0;
            $user_companies = DB::connection('mysql')->table('users_companies')->where('user_id',$request->id)->where('company', $l->company)->count();
            if($user_companies > 0){
                $cnt = 1;
            }
            $getcompany[] = ['company' => $l->company, 'checked' => $cnt];       
        }       
        $uc = DB::connection('mysql')->table('users_companies')->where('user_id',$request->id)->where('company', 'All')->count();

        return response()->json(['success' => true, 'company' => $getcompany, 'uc' => $uc ]);
    }

    public function updateUserCompanies(Request $request){
        //dd($request->all());

        DB::table('users_companies')->where('user_id', $request->userid)->delete();

        if(in_array("All",$request->companies)){
            $query =DB::connection('mysql')->table('users_companies')->insert([  
                'user_id'    => $request->userid,
                'company'    => "All"
            ]);
        }else{
            foreach($request->companies as $c){
                $query =DB::connection('mysql')->table('users_companies')->insert([  
                    'user_id'    => $request->userid,
                    'company'    => $c
                ]);
            }
        }
        
        $user = DB::table('users')->where('id', $request->userid)->first();
        $name = $user->firstname ." ". $user->lastname;
        if($query){                       
            return redirect('/users')->with('status','User '.$name.' company has been updated!');
        }else{
            return redirect('/users')->with('error','Error updating company for user '.$name.'!');
        } 

    }

}
