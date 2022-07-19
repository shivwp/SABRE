<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\JobCategory;
use App\Models\JobMeta;
use App\Models\UserMeta;
use App\Models\User;
use App\Models\JobApplied;
use App\Models\TaskLog;

class TaskLogController extends Controller
{
    public function index(){
        $d['title'] = "Task Log";
       
        // dd($d['model']);
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=TaskLog::join('users','task_log.user_id','=','users.id')
                    ->join('jobs','task_log.job_id','jobs.id')->select('users.*','jobs.*','task_log.*','users.id as users_auto_id','jobs.id as job_auto_id');
        // dd($q);
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['task']=$q->paginate($pagination)->withQueryString();
        // dd($d['task']);
        return view('admin.task-log.index',$d); 
    }

    public function create()
    {
       
    }
    public function store(Request $request)
    {
        $task_log = TaskLog::where('user_id',$request->user_id)->where('job_id',$request->job_id)->update([
            'task_status'=>1
        ]);
        return redirect()->back();
    }
    public function edit($id)
    {
        
    }

    public function update(UpdateUserRequest $request, User $user)
    {

    }

    public function show($id)
    {
        
    }
    public function destroy($id)
    {
        
    }
}
