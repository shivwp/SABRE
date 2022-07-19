<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\UserBids;
use App\Models\UserWalletTransection;
use App\Models\User;
use App\Models\UserAvailability;
use App\Models\UserMeta;
use App\Models\States;
use App\Models\Countries;
use App\Models\Transaction;
use Hash;
use Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportCustomer;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $title = "Users";
        $buton_name = "Add New";
        
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=User::select('*')->where('is_otp_verified',1)->orderBy('id','DESC');
            if($request->search){
                $q->where('name', 'like', "%$request->search%");
            }
        $users=$q->paginate($pagination)->withQueryString();
        return view('admin.users.index', compact('users','title','buton_name'));
    }
    public function index2()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $title = "Users";
        $buton_name = "Add New"; 
        $users = User::join('address', 'address.user_id', '=', 'users.id')->where('address.is_default', '=', '1')->get();
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $users =User::paginate($pagination)->withQueryString();
        return view('admin.users.index2', compact('users','title','buton_name'));
    }
    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $title = "User Add";
            $roles = Role::all()->pluck('title', 'id');
            $states = States::where('country_id',231)->get();
        return view('admin.users.create', compact('roles','title','states'));
    }
    public function store(Request $request)
    {
        // dd($request);
        $user = User::updateOrCreate(['id'=>$request->id],[
            'name'          => $request->name,
            'email'         => $request->email,
            'dob'           => $request->dob,
            'gender'        => $request->gender,
            'phone'         => $request->phone,
            'user_status'   => $request->user_status,
            'address'       => $request->home_address,
            'city'          => $request->city,
            'state'         => $request->state,
            'zip'           => $request->zip,
            'discription'   => $request->short_bio,
        ]);
        if($request->password != null){
            $password = Hash::make($request->password);
            User::where('id',$user->id)->update([
                'password'      => $password,
            ]);
        }
        $user->roles()->sync($request->input('role'));

        if($request->hasfile('pro_image'))
        {
            $file = $request->file('pro_image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('profile-image/', $filename);
            User::where('id',$user->id)->update([
                'profile_image' => ('profile-image/'.$filename)
            ]);
        }
        $user->update();
        if(!empty($request->id)){
            $usergenerate_id = $request->id;
        }else{
            $usergenerate_id = $user->id;
        }
        $path = 'certificate/' . $usergenerate_id;

        if(!empty($request->experience)){
            $experience_req = implode(',',$request->experience);
        }
         if(!empty($request->medical)){
            $medical_req = implode(',',$request->medical);
        }
        $usermeta = [
            'guard_card'                    => isset($request->guard_card) ? $request->guard_card : '',
            'guard_card_number'             => isset($request->guard_card_number) ? $request->guard_card_number : '',
            'firearm_license'               => isset($request->firearm_license) ? $request->firearm_license : '',
            'fire_lice_number'              => isset($request->fire_lice_number) ? $request->fire_lice_number : '',
            'ca_ccw'                        => isset($request->ca_ccw) ? $request->ca_ccw : '',
            'ca_ccw_number'                 => isset($request->ca_ccw_number) ? $request->ca_ccw_number : '',
            'hr_cert'                       => isset($request->hr_cert) ? $request->hr_cert : '',
            'hr_agency'                     => isset($request->hr_agency) ? $request->hr_agency : '',
            'any_other_license'             => isset($request->any_other_license) ? $request->any_other_license : '',
            'cpr'                           => isset($request->cpr) ? $request->cpr : '',
            'experience'                    => isset($experience_req) ? $experience_req : '',
            'medical'                       => isset($medical_req) ? $medical_req : '',
            'first_aid'                     => isset($request->first_aid) ? $request->first_aid : '',
            'trauma'                        => isset($request->trauma) ? $request->trauma : '',
            'advance_first'                 => isset($request->advance_first) ? $request->advance_first : '',
            'head_shot'                     => isset($request->head_shot) ? $request->head_shot : '',
            'invest_exp'                    => isset($request->invest_exp) ? $request->invest_exp : '',
            'last_drive'                    => isset($request->last_drive) ? $request->last_drive : '',
            'vaccinated'                    => isset($request->vaccinated) ? $request->vaccinated : 'no',

            'bsis_guard_card_certificate'   => 
            ($request->hasfile('upload_certificate')) ? $this->uploadDocuments($request->file('upload_certificate'), $path) : (($request->upload_certificate_old) ? $request->upload_certificate_old : ''),

            'bsis_exposed_certificate'=>
            ($request->hasfile('upload_certificate_2')) ? $this->uploadDocuments($request->file('upload_certificate_2'), $path) : (($request->upload_certificate_2_old) ? $request->upload_certificate_2_old : ''),

            'ca_ccw_certificate' => 
            ($request->hasfile('upload_certificate_3')) ? $this->uploadDocuments($request->file('upload_certificate_3'), $path) : (($request->upload_certificate_3_old) ? $request->upload_certificate_3_old : ''),

            'hr_certificate'  => 
            ($request->hasfile('upload_certificate_4')) ? $this->uploadDocuments($request->file('upload_certificate_4'), $path) : (($request->upload_certificate_4_old) ? $request->upload_certificate_4_old : ''),

            'medical_training_certificate' => 
            ($request->hasfile('upload_certificate_5')) ? $this->uploadDocuments($request->file('upload_certificate_5'), $path) : (($request->upload_certificate_5_old) ? $request->upload_certificate_5_old : ''),

            //OnBoarding Docs
            'drug_alcohol'=> 
             ($request->hasfile('drug_alcohol')) ? $this->uploadDocuments($request->file('drug_alcohol'), $path) : (
                ($request->drug_alcohol_old) ? $request->drug_alcohol_old : ''),

            'emp_demo'=> 
            ($request->hasfile('emp_demo')) ? $this->uploadDocuments($request->file('emp_demo'), $path) : (
                ($request->emp_demo_old) ? $request->emp_demo_old : ''),

            'rule_force'=> 
            ($request->hasfile('rule_force')) ? $this->uploadDocuments($request->file('rule_force'), $path) : (
                ($request->rule_force_old) ? $request->rule_force_old : ''),

            'sabree_nda'=> 
            ($request->hasfile('sabree_nda')) ? $this->uploadDocuments($request->file('sabree_nda'), $path) : (
                ($request->sabree_nda_old) ? $request->sabree_nda_old : ''),

            'vaccination_card'=> 
            ($request->hasfile('vaccination_card')) ? $this->uploadDocuments($request->file('vaccination_card'), $path) : (
                ($request->vaccination_card_old) ? $request->vaccination_card_old : ''),
        ];
        
        // dd($request);
        if(!empty($request->id)){
            $usergenerate_id = $request->id;
        }else{
            $usergenerate_id = $user->id;
        }
        $this->updateUserAllMeta($usergenerate_id,$usermeta);
        $user_avail = UserAvailability::updateOrCreate(['user_id'=>$usergenerate_id],[
            'mon_open'          => isset($request->mon_open) ? $request->mon_open : null,
            'mon_close'         => isset($request->mon_close) ? $request->mon_close : null,
            'tue_open'          => isset($request->tue_open) ? $request->tue_open : null,
            'tue_close'         => isset($request->tue_close) ? $request->tue_close : null,
            'wed_open'          => isset($request->wed_open) ? $request->wed_open : null,
            'wed_close'         => isset($request->wed_close) ? $request->wed_close : null,
            'thu_open'          => isset($request->thu_open) ? $request->thu_open : null,
            'thu_close'         => isset($request->thu_close) ? $request->thu_close : null,
            'fri_open'          => isset($request->fri_open) ? $request->fri_open : null,
            'fri_close'         => isset($request->fri_close) ? $request->fri_close: null,
            'sat_open'          => isset($request->sat_open) ? $request->sat_open : null,
            'sat_close'         => isset($request->sat_close) ? $request->sat_close : null,
            'sun_open'          => isset($request->sun_open) ? $request->sun_open : null,
            'sun_close'         => isset($request->sun_close) ? $request->sun_close : null,
            'is_available'     => isset($request->is_available) ? $request->is_available : 0,

        ]);
        $type='User';
        \Helper::addToLog('User Updated', $type);
        return redirect()->back()->with('success','message');
    }
    public function storeTransection(Request $request)
    {

    }
    public function blockUser($id)
    {
       User::where('id',$id)->update([
            'block' => '1'
       ]);

       return back();
    }
    public function edit($id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $d['title'] = "User Edit";

        $d['roles'] = Role::all()->pluck('title', 'id');

        $d['user'] = User::findOrFail($id);
        $d['user_avail'] = UserAvailability::where('user_id',$id)->first();
        // dd($user_avail->is_available);
        $d['states'] = States::where('country_id',231)->get();
        $d['user']->load('roles');
        $d['user_meta'] = $this->getUserMeta($id);
        if(!empty($d['user_meta']['experience'])){
            $d['experience_data'] = $d['user_meta']['experience'];
            // dd($d['experience_data']);
        }
        if(!empty($d['user_meta']['medical'])){
            $d['medical_data'] = $d['user_meta']['medical'];
        }
        
        return view('admin.users.create', $d);
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
    }
    public function show($id)
    {

        
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::findOrFail($id);

        $user_role = DB::table('role_user')->where('user_id',$id)->delete();
        $user->delete();

        return back();
    }
    public function massDestroy(MassDestroyUserRequest $request)
    {

        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);


    }
    public function storeCradit(Request $request){
       

    }
    public function customer(Request $request){
        
    }
    public function importView(Request $request){

       

    }

    public function importCustomer(Request $request){
        
    }

    public function clearCache(Request $request){

        return view('admin.clear-cache');
    }

    public function exportUsers(Request $request){
        return Excel::download(new ExportUser, 'users.xlsx');
    }

}



