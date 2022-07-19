<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\JobCategory;
use App\Models\JobMeta;
use App\Models\UserMeta;
use App\Models\JobApplied;
use App\Models\TaskLog;

class JobController extends Controller
{
    public function index(Request $request){
        $d['title'] = "Job";
       
        // dd($d['model']);
        $d['buton_name'] = "ADD NEW";

        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=Jobs::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        if(!empty($request->status)){
            $q->where('status', 'like', "%$request->status%");  
        }
        $d['jobs']=$q->paginate($pagination)->withQueryString();
        return view('admin.jobs.index',$d); 
    }

    public function create(){

        $d['title'] = "Job Add";
        $d['jobs'] = Jobs::all();
        $d['job_cate'] = JobCategory::all();
        return view('admin.jobs.create', $d);
    }
    public function store(Request $request)
    {
        // dd($request);
        
        $d['jobs'] = Jobs::all();
        $jobs = Jobs::updateOrCreate(['id'=>$request->id],[

            'client_name'           => $request->client_name,
            'title'                 => $request->job_title,
            'number_of_agents'      => $request->agent_number,
            'job_location'          => $request->location,
            'job_address'           => $request->address,
            'assignment_start_date' => $request->start_assign_date,
            'assignment_end_date'   => $request->end_assign_date,
            'start_time'            => $request->start_time,
            'end_time'              => $request->end_time,
            'point_contactname'     => $request->point_contact,
            'point_phonenumber'     => $request->point_contact_phone,
            'agent_attire'          => $request->agent_attire,
            'armed'                 => $request->armed,
            'concealed'             => $request->conceal,
            'status'                => $request->status,
            'category'              => $request->category,
            'pay_rate'              => $request->pay_rate,
        ]);

        if($request->hasfile('profile_image'))
        {
            $file = $request->file('profile_image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('profile-image/', $filename);
            Jobs::where('id',$jobs->id)->update([
                'profile_image' => $filename
            ]);
        }
        $type='Job ';
        \Helper::addToLog('Job Updated', $type);
        
        $jobs->update();

        return redirect()->route('dashboard.jobs.index');
    }
    public function edit($id)
    {
        $d['title'] = "Job Edit";
        $d['buton_name'] = "Edit";
        $d['jobs'] = Jobs::findorfail($id);
        $d['job_cate'] = JobCategory::all();
        return view('admin.jobs.create', $d);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

    }

    public function show($id)
    {
        $d['title'] = "All Assigned Guard List";
        $d['task']=TaskLog::join('users','task_log.user_id','=','users.id')->join('jobs','task_log.job_id','jobs.id')->where('task_log.job_id','=',$id)->select('users.*','jobs.*','task_log.*','users.id as users_auto_id','jobs.id as job_auto_id')->get();
        // dd($d['task']);
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $ajob = JobApplied::join('users','job_applied.user_id','=','users.id')
                            ->where('job_applied.job_id','=',$id)
                            ->select('users.*','job_applied.*','users.id as users_auto_id','job_applied.id as jobs_auto_id','job_applied.status as job_status');
        $d['jobs'] = Jobs::findorfail($id);
        // dd($d['jobs']);
        $d['job_cate'] = JobCategory::all();                                
        $d['asigne_job']=$ajob->paginate($pagination)->withQueryString();      
        $d['job_meta']   = $this->getJobMeta($id);
        return view('admin.jobs.view', $d);
    }
    public function destroy($id)
    {
        $job_delete = Jobs::where('id', $id)->delete();
        return back();
    }
}
