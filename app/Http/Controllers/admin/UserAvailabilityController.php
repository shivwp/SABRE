<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAvailability;

class UserAvailabilityController extends Controller
{
    public function index(){
        $d['title'] = "User Availability";
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=UserAvailability::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['user_avail']=$q->paginate($pagination)->withQueryString();
        return view('admin.user-availability.index',$d); 
    }

    public function create(){

        $d['title'] = "Job Add";
        $d['user_avail'] = UserAvailability::all();

        return view('admin.user-availability.create', $d);
    }
    public function store(Request $request)
    {

        $d['job_cate'] = JobCategory::all();
        $job_cate = JobCategory::updateOrCreate(['id'=>$request->id],[
            'title'    => $request->title,
        ]);
        return redirect()->route('dashboard.user-availability.index');
    }
    public function edit($id)
    {
        $d['title'] = "Job Edit";
        $d['buton_name'] = "Edit";
        $d['job_cate'] = JobCategory::findorfail($id);
        return view('admin.user-availability.create', $d);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

    }

    public function show()
    {

    }
    public function destroy($id)
    {
        $job_cate = JobCategory::where('id', $id)->delete();
        return back();
    }
}
