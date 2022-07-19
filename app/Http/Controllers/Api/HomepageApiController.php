<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\Setting;
use App\Models\Faqs;
use Validator;
use Gate;
use Auth;

class HomepageApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = '';
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if(!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!']);
        }

        $data=Jobs::where('status','publish')->get();
        $home_data = [];
         if(count($data) > 0){
            foreach ($data as $key => $value) {
                $date = date_format($value->created_at,'M d,Y');
                $home_data[] = [
                    'id' => isset($value->id) ? $value->id : "",
                    'title' => isset($value->title) ? $value->title : "",
                    'client_name' => isset($value->client_name) ? $value->client_name : "",
                    'category' => isset($value->category) ? $value->category : "",
                    'profile_image' => isset($value->profile_image) ? $value->profile_image : "",
                    'number_of_agents' => isset($value->number_of_agents) ? $value->number_of_agents : "",
                    'job_location' => isset($value->job_location) ? $value->job_location : "",
                    'job_address' => isset($value->job_address) ? $value->job_address : "",
                    'pay_rate' => isset($value->pay_rate) ? $value->pay_rate : "",
                    'assignment_start_date' => isset($value->assignment_start_date) ? $value->assignment_start_date : "",
                    'assignment_end_date' => isset($value->assignment_end_date) ? $value->assignment_end_date : "",
                    'start_time' => isset($value->start_time) ? $value->start_time : "",
                    'end_time' => isset($value->end_time) ? $value->end_time : "",
                    'point_contactname' => isset($value->point_contactname) ? $value->point_contactname : "",
                    'point_phonenumber' => isset($value->point_phonenumber) ? $value->point_phonenumber : "",
                    'agent_attire' => isset($value->agent_attire) ? $value->agent_attire : "",
                    'armed' => isset($value->armed) ? $value->armed : "",
                    'concealed' => isset($value->concealed) ? $value->concealed : "",
                    'status' => isset($value->status) ? $value->status : "",
                    'created_date' => isset($date) ? $date : "",
                ];
            }
            return response()->json(['status' => true, 'message' => "List Of Available Assignments", 'data' => $home_data], 200);
         }
         else{
            return response()->json(['status' => false, 'message' => "No Available Assignments", 'data' => []], 200);
         }

    }
    public function singleJob(Request $request)
    {
        $user = '';
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if(!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!']);
        }
        $validator = Validator::make($request->all(), [
            'assignment_id' => 'required|exists:jobs,id',
        ]);
        
        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => null], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $single_data=Jobs::where('id',$assignment_id)->first();
        if(!empty($single_data)){
            return response()->json(['status' => true, 'message' => "Details of Single Assignments", 'data' => $single_data], 200);
        }
         else{
            return response()->json(['status' => false, 'message' => "No Details", 'data' => []], 200);
        }

    }

    public function faq_list(){

        $sop_list = Faqs::all();
        if(count($sop_list) > 0){
            foreach($sop_list as $value){
                $sop_data[] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'description' => $value->description,
                ];
            }
            return response()->json(['status' => true, 'message' => "SOP List", 'data' => $sop_data], 200);
        }else{
            return response()->json(['status' => false, 'message' => "No Record Found", 'data' => []], 200);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response

     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response

     */
    public function destroy($id)
    {
        //
    }

}



