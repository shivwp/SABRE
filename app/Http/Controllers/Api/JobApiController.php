<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Str;
use App\Models\Mails;
use App\Models\CategorySubscription;
use App\Models\JobApplied;
use App\Models\JobCategory;
use App\Models\TaskLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailtemp;  
use DB;
use Gate;
use Carbon\Carbon;

class JobApiController extends Controller
{
    // User Login
    public function applyJob(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }

        $user_id = $user->id;

        if (empty($user_id)) {
            return response()->json(['status' => false, 'message' => "user not found"], 200);
        }
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()),'data'=>null], 200);
        }
        $parameters = $request->all();
        extract($parameters);

        $job_data = JobApplied::updateOrCreate([
            'job_id'            => $job_id,
            'user_id'           => $user_id,
        ],[
            'job_id'            =>$job_id,
            'user_id'           =>$user_id,
            'status'            =>'pending',
            'applied_date'      => Carbon::now(),
        ]);
        return response()->json(['status' => true, 'message' => "Job Applied successfully", 'data' => $job_data], 200);
    }

    public function jobCategory(Request $request){

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $user_id = $user->id;
        }

        
        if (empty($user_id)) {
            return response()->json(['status' => false, 'message' => "User not Found"], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $data = JobCategory::all();
        if(!empty($data)){
            foreach($data as $cate){
                $stts = false;
                $cate_data = CategorySubscription::where('user_id',$user_id)->get();
                if(count($cate_data) > 0){
                    foreach($cate_data as $vlu){
                        $dataa_id = $vlu->cate_id;
                            if($dataa_id == $cate->id){
                                $stts = true;
                            }
                    }
                }
                $category[]=[
                    'id'        =>$cate->id,
                    'title'     =>$cate->title,
                    'checked'   =>$stts,
                ];
            }
            return response()->json(['status' => true, 'message' => "Subscription / Category List", 'data' => $category], 200);
        }else{
            return response()->json(['status' => false, 'message' => "No Subscription / Category List", 'data' => null], 200);

        }
        
    }

    public function userAssignment(Request $request){

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }

        $user_id = $user->id;
        if (empty($user_id)) {
            return response()->json(['status' => false, 'message' => "User not Found"], 200);
        }

        $parameters = $request->all();
        extract($parameters);
        $assigned_task = DB::table('job_applied')
                    ->join('users', 'users.id', '=', 'job_applied.user_id')
                    ->join('jobs', 'jobs.id', '=', 'job_applied.job_id')
                    ->where('job_applied.user_id',$user_id)
                    ->where('job_applied.status','assigned')
                    ->select('users.*','job_applied.*','jobs.*','users.id as users_auto_id','jobs.id as job_auto_id','job_applied.id as jobapplied_auto_id','jobs.created_at as job_date','job_applied.status as jobapplied_status')
                    ->get();
                    // dd($assigned_task);
        if(count($assigned_task) > 0){    
            foreach($assigned_task as $task){

                $task_data = TaskLog::where('user_id',$task->users_auto_id)->where('job_id',$task->job_auto_id)->first();
                $task_status = $task_data->task_status;
                $is_check = false;
                
                if(($task_data->arrive_on_site == 1) && ($task_data->document_mileage == 1) && ($task_data->call_local == 1)){
                    $is_check = true;
                }

                $date = date('M d,y', strtotime($task->job_date));
                $user_Assignment[] = [
                    'title'                 => $task->title,
                    'client_name'           => $task->client_name,
                    'category'              => $task->category,
                    'number_of_agents'      => $task->number_of_agents,
                    'job_location'          => $task->job_location,
                    'assignment_start_date' => $task->assignment_start_date,
                    'assignment_end_date'   => $task->assignment_end_date,
                    'start_time'            => $task->start_time,
                    'end_time'              => $task->end_time,
                    'pay_rate'              => $task->pay_rate, 
                    'job_status'            => $task->jobapplied_status,   
                    'date'                  => $date, 
                    'is_done'               => (isset($task_status) && $task_status == 1) ? true : false,
                    'is_check'              => $is_check,
                ];

            }
            return response()->json(['status' => true, 'message' => "Task Assigned", 'data' => $user_Assignment], 200);
        }else{
            return response()->json(['status' => false, 'message' => "No Task Assigned", 'data' => null], 200);
        }
    }

}