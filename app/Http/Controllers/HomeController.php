<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Models\LogActivity;
use App\Models\Role;
use App\Models\User;
use App\Models\Jobs;
use App\Models\JobApplied;
use App\Models\TaskLog;
use DB;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $d['total_job'] = Jobs::count();
        $d['guard']=DB::table('users')
                    ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
                    ->select('users.*','users.id as users_auto_id')
                    ->where('role_user.role_id', '=', 6)->count();
        $d['complete_job'] = Jobs::where('status','complete')->count();
        $d['assign_guard']=JobApplied::where('status','assigned')->distinct('user_id')->count();
        $d['checklist'] = TaskLog::count();
        $d['checklist_arrived'] = TaskLog::where('arrive_on_site',1)->count();
        $d['checklist_document'] = TaskLog::where('document_mileage',1)->count();
        $d['checklist_call'] = TaskLog::where('call_local',1)->count();
        return view('index',$d);
    }
     public function myTestAddToLog()
    {
        \Helper::addToLog('My Testing Add To Log.');
        dd('log insert successfully.');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logActivity()
    {  $data['title'] = "Logs";
        $logs = \Helper::logActivityLists();
        return view('logActivity',compact('logs'));
    }
    public function logsdelete($id)
    {
        $log = LogActivity::findOrFail($id);
        $log->delete();
        return back();
    }
}
