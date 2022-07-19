<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Jobs;
use App\Models\JobApplied;
use Hash;
use DB;

class GuardController extends Controller
{
    public function index(Request $request){
        $d['title'] = "Guard";
       
        // dd($d['model']);
        // $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=DB::table('users')
                    ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                    ->select('users.*','users.id as users_auto_id')
                    ->where('role_user.role_id', '=', 6)
                    ->where('users.is_otp_verified', '=', 1);
        if(!empty($request->search)){
            $q->where('name', 'like', "%$request->search%");  
        }
        $d['guard']=$q->paginate($pagination)->withQueryString();
        // dd($d['guard']);
        return view('admin.guard.index',$d); 
    }

    public function create(){

    }
    public function store(Request $request)
    {
        
    }
    public function edit($id)
    {
        
    }

    public function update(UpdateUserRequest $request, User $user)
    {

    }

    public function show($id)
    {
        $d['title'] = "Old Task History";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $job_old=DB::table('job_applied')
                    ->leftjoin('jobs', 'jobs.id', '=', 'job_applied.job_id')
                    ->where('job_applied.user_id', '=', $id);

        $d['guard']=$job_old->paginate($pagination)->withQueryString();
        // dd($d['guard']);
        return view('admin.guard.create', $d);
    }
    public function destroy($id)
    {
        
    }

}
