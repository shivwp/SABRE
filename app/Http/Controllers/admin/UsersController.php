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
        $users = User::all();
        $pagination=10;
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $q=User::select('*')->orderBy('id','DESC');
            if($request->search){
                $q->where('first_name', 'like', "%$request->search%");
            }
        $users=$q->paginate($pagination)->withQueryString();
        return view('admin.users.index', compact('users','title','buton_name'));
    }
    public function index2()
    {
        abort_if(Gate::denies('vuser_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        //dd($request);
        $password = Hash::make($request->password);

        $user = User::updateOrCreate(['id'=>$request->id],[
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => $password,
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
        $user->roles()->sync($request->input('role'));

        if($request->hasfile('upload_certificate'))
        {
            $file = $request->file('upload_certificate');
            $extention = $file->getClientOriginalExtension();
            $upload_certificate = time().'.'.$extention;
            $file->move('certificate/', $upload_certificate);
        }

        if($request->hasfile('upload_certificate_2'))
        {
            $file2 = $request->file('upload_certificate_2');
            $extention2 = $file2->getClientOriginalExtension();
            $upload_certificate_2 = time().'.'.$extention2;
            $file2->move('certificate/', $upload_certificate_2);
        }
        if($request->hasfile('upload_certificate_3'))
        {
            $file3 = $request->file('upload_certificate_3');
            $extention3 = $file3->getClientOriginalExtension();
            $filename3 = time().'.'.$extention3;
            $file3->move('certificate/', $filename3);
        }
        if($request->hasfile('upload_certificate_4'))
        {
            $file4 = $request->file('upload_certificate_4');
            $extention4 = $file4->getClientOriginalExtension();
            $filename4 = time().'.'.$extention4;
            $file4->move('certificate/', $filename4);
        }
        if($request->hasfile('upload_certificate_5'))
        {
            $file5 = $request->file('upload_certificate_5');
            $extention5 = $file5->getClientOriginalExtension();
            $filename5 = time().'.'.$extention5;
            $file5->move('certificate/', $filename5);
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
            'military'                      => isset($request->military) ? $request->military : '',
            'police'                        => isset($request->police) ? $request->police : '',
            'cpr'                           => isset($request->cpr) ? $request->cpr : '',
            'close_pro'                     => isset($request->close_pro) ? $request->close_pro : '',
            'exe_pro'                       => isset($request->exe_pro) ? $request->exe_pro : '',
            'advance_course'                => isset($request->advance_course) ? $request->advance_course : '',
            'first_aid'                     => isset($request->first_aid) ? $request->first_aid : '',
            'trauma'                        => isset($request->trauma) ? $request->trauma : '',
            'advance_first'                 => isset($request->advance_first) ? $request->advance_first : '',
            'head_shot'                     => isset($request->head_shot) ? $request->head_shot : '',
            'invest_exp'                    => isset($request->invest_exp) ? $request->invest_exp : '',
            'last_drive'                    => isset($request->last_drive) ? $request->last_drive : '',
            'bsis_guard_card_certificate'=> isset($upload_certificate) ? $upload_certificate : (isset($request->upload_certificate_old) ? $request->upload_certificate_old : ''),
            'bsis_exposed_certificate'=>isset($upload_certificate_2) ? $upload_certificate_2 : (isset($request->upload_certificate_2_old) ? $request->upload_certificate_2_old : ''),
            'ca_ccw_certificate' => isset($filename3) ? $filename3 : (isset($request->upload_certificate_3_old) ? $request->upload_certificate_3_old : ''),
            'hr_certificate'  => isset($filename4) ? $filename4 : (isset($request->upload_certificate_old) ? $request->upload_certificate_old : ''),
            'medical_training_certificate' => isset($filename5) ? $filename5 : (isset($request->upload_certificate_5_old) ? $request->upload_certificate_5_old : ''),
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
            'not_available'     => isset($request->not_available) ? $request->not_available : 'off',

        ]);
        return redirect()->route('dashboard.users.index');
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
        $title = "User Edit";

        $roles = Role::all()->pluck('title', 'id');

        $user = User::findOrFail($id);
        $user_avail = UserAvailability::where('user_id',$id)->first();
        // dd($user_avail->not_available);
        $states = States::where('country_id',231)->get();
        $user->load('roles');
        $user_meta = $this->getUserMeta($id);
        return view('admin.users.create', compact('roles', 'user','title','user_avail','states','user_meta'));
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
    }
    public function show($id)
    {

        $d['title']         = "User";

        $user = User::where('id',$id)->first();

        $userOrder = Order::where('user_id',$id)->where('parent_id',0)->get();

        foreach($userOrder as $key => $val){

         $orderMeta = OrderMeta::where('order_id',$val->id)->pluck('meta_value','meta_key');

         $OrderedProducts = OrderedProducts::where('order_id',$val->id)->get();

         $userOrder[$key]['order_meta'] = $orderMeta;

         $userOrder[$key]['order_product'] = $OrderedProducts;

        }

        $topsellingproduct = Order::where('user_id',$id)

        ->join('ordered_products', 'ordered_products.order_id', '=', 'orders.id')

        ->join('products', 'products.id', '=', 'ordered_products.product_id')

        ->selectRaw('products.*, SUM(ordered_products.quantity) AS quantity_sold')

        ->groupBy(['products.id']) // should group by primary key

        ->orderByDesc('quantity_sold')

        ->take(5) // 20 best-selling products

        ->get();

        $topsellingcategory = Order::where('user_id',$id)

        ->join('ordered_products', 'ordered_products.order_id', '=', 'orders.id')

        ->join('categories', 'categories.id', '=', 'ordered_products.category')

        ->selectRaw('categories.*')

        ->groupBy(['categories.id']) 

        ->take(5) // 20 best-selling products

        ->get();
        $usercart = Cart::where('user_id',$id)->get();

        foreach($usercart as $cart_key => $cart_val){

            $pro = Product::where('id',$cart_val->product_id)->first();

            $usercart[$cart_key]['pro_name'] = $pro->pname;
        }

        $d['user']         = $user;

        $d['userOrder']         = $userOrder;

        $d['topsellingproduct']         = $topsellingproduct;

        $d['topsellingcategory']         = $topsellingcategory;

        $d['usercart']         = $usercart;

        $usersbid = UserBids::where('user_id',$id)->get();

        foreach($usersbid as $bid_key => $bid_val){

            $user = User::where('id',$bid_val->user_id)->first();
            $product = Product::where('id',$bid_val->product_id)->first();
            $usersbid[$bid_key]['user'] = !empty($user->name) ? $user->name : '';
            $usersbid[$bid_key]['product'] = !empty($product->pname) ? $product->pname : '';

        }


        $d['usersbid']         = $usersbid;

        return view('admin.users.show',$d);
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
        $d['title'] = "Users";

        $pagination=10;  

        if(isset($_GET['paginate'])){

            $pagination=$_GET['paginate'];

        }

        $d['users']=User::query()

        ->whereHas('roles', function($query){ 

        $query->where('title','=', 'User');

        })->where('user_wallet','!=',0)->paginate($pagination)->withQueryString();
        return view('admin.users.store-cradit',$d);

    }
    public function customer(Request $request){
        $d['title'] = "Customers";

         $pagination=10;  

        if(isset($_GET['paginate'])){

            $pagination=$_GET['paginate'];

        }

        $setting = Role::where('title', 'User')->first()->users();
        if($request->search){

            $setting->where('first_name', 'like', "%$request->search%"); 

        }
        $d['setting']=$setting->paginate($pagination)->withQueryString();
        return view('admin.customer',$d);
    }
    public function importView(Request $request){

        return redirect('/dashboard/product');

    }

    public function importCustomer(Request $request){

        $fileName = time().'_'.request()->importfile->getClientOriginalName();

          Excel::import(new ImportCustomer, $request->file('importfile')->storeAs('product-csv', $fileName));

        return redirect()->back();

    }

    public function clearCache(Request $request){

        return view('admin.clear-cache');

    }

    public function exportUsers(Request $request){
        return Excel::download(new ExportUser, 'users.xlsx');
    }

}



