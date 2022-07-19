<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCategory;

class JobCategoryController extends Controller
{
    public function index(Request $request){
        $d['title'] = "Job Category";
       
        // dd($d['model']);
        $d['buton_name'] = "ADD NEW";
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=JobCategory::select('*');
        if(!empty($request->search)){
            $q->where('title', 'like', "%$request->search%");  
        }
        $d['job_cate']=$q->paginate($pagination)->withQueryString();
        return view('admin.job-category.index',$d); 
    }

    public function create(){

        $d['title'] = "Job Add";
        $d['job_cate'] = JobCategory::all();

        return view('admin.job-category.create', $d);
    }
    public function store(Request $request)
    {
        // dd($request);
        
        $d['job_cate'] = JobCategory::all();
        $job_cate = JobCategory::updateOrCreate(['id'=>$request->id],[
            'title'    => $request->title,
        ]);
        $type='Job category';
        \Helper::addToLog('Job category update', $type);
        return redirect()->route('dashboard.job-category.index');
    }
    public function edit($id)
    {
        $d['title'] = "Job Edit";
        $d['buton_name'] = "Edit";
        $d['job_cate'] = JobCategory::findorfail($id);
        return view('admin.job-category.create', $d);
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

