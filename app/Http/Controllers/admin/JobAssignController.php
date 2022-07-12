<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\User;
use App\Models\Role;
use App\Models\JobCategory;
use App\Models\JobApplied;
use Carbon\Carbon;
use DB;

class JobAssignController extends Controller
{
    public function index(){
        $d['title'] = "Applied Job ";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }

        // $jobs = JobApplied::with(['users','jobs'])->get();
        // dd($jobs);
        // $q=JobApplied::select('*');
        $q=DB::table('jobs')
                    ->join('job_applied', 'job_applied.job_id', '=', 'jobs.id')
                    ->join('users', 'users.id', '=', 'job_applied.user_id')
                    // ->leftjoin('user_meta', 'user_meta.user_id', '=', 'users.id')
                    ->select('users.*','job_applied.*','jobs.*','users.id as users_auto_id','job_applied.id as jobs_auto_id','job_applied.status as job_status');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['job_applied']=$q->paginate($pagination)->withQueryString();
        // dd($d['job_applied']);
        return view('admin.job-apply.index',$d); 
    }

    public function create(){

        $d['title'] = "Apply";
        $d['jobs'] = Jobs::all();
        $d['users'] = Role::where('title','guard')->first()->users;
        // dd($d['users']);
        $d['job_cate'] = JobCategory::all();
        $d['job_applied'] = JobApplied::all();
        return view('admin.job-apply.create', $d);
    }
    public function store(Request $request)
    {
        // dd($request);
        $d['jobs'] = Jobs::all();
        $job_applied = JobApplied::updateOrCreate(['id'=>$request->id],[

            'job_id'            => $request->job_id,
            'user_id'           => $request->user_id,
            'applied_date'      => Carbon::now()->toDateTimeString(),
            'status'            => 'pending',
        ]);
        if(!empty($request->status))
        {
           JobApplied::where('id','=',$request->id)->update([
            'status' => $request->status,
            ]);
           return redirect()->route('dashboard.job-apply.index')->with('assign','Job Assigned successfully');
        }
        
        return redirect()->route('dashboard.job-apply.index');
    }
    public function edit($id)
    {
        $d['title'] = "Apply";
        $d['buton_name'] = "Edit";
        $d['jobs'] = Jobs::all();
        $d['job_applied'] = JobApplied::findorfail($id);
        $d['users'] = Role::where('title','guard')->first()->users;
        $d['job_cate'] = JobCategory::all();
        return view('admin.job-apply.create', $d);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

    }

    public function show()
    {

    }
    public function destroy($id)
    {
        $JobApplied = JobApplied::where('id', $id)->delete();
        return back();
    }
}
