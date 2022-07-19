<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\UserDeviceToken;
use App\Models\CategorySubscription;
use Validator;
use Auth;
use DB;
use Carbon;

class NotificationsApiController extends Controller
{
  
    public function index(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } 

        $user_id = $user->id;

        $Notifications = Notifications::select('title','body','image','status','created_at')->where('user_id',$user_id)->orWhere('user_id','0')->get();

        if(count($Notifications)>0) {

            foreach($Notifications as $val){
                    $date = $val->created_at;

                    if($date->isToday()){
                        $time = 'Today';
                    }else{
                        $time = $date->format('F d,Y');
                    }
                    // $val->image = url('notification-image/'.$val->image);
                    $notifications_data[$time][] = [
                        'title'=>$val->title,
                        'body'=>$val->body,
                        'time'=>$val->created_at->diffForHumans(),
                    ];
                    // $Notifications[$key]['created_date'] = date("F d,Y", strtotime($val->created_at));
                    // $Notifications[$key]['time'] = $val->created_at->diffForHumans();
                    // unset($val->created_at);
            }

            return response()->json([ 'status'=> true ,'message' => "Notifications Data",'data'=>$notifications_data], 200);
        }
        else{
           return response()->json([ 'status'=> false ,'message' => "No Notifications",'data'=>null], 200);  
        }
    }

    public function sendNotification(Request $request){

        $validator = Validator::make($request->all(), [
            'cate_id' => 'required|exists:job_category,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => null], 200);
        }
        $parameters = $request->all();
        extract($parameters);

        $cate_subscribe_user = CategorySubscription::select('user_id')->where('cate_id',$cate_id)->get();
        if(count($cate_subscribe_user) > 0){
            foreach($cate_subscribe_user as $values){
                $ids = $values->user_id;
                $user_id = $ids;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
            //'sender_id' => 'required',
            //'image' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        } 
        $url=null;
         $Notification = Notifications::updateOrCreate(['id' => $request->id], [
            'title'         => $request->title,
            'user_id'       => $user_id,
            'body'          => $request->body,
            'type'          => 'update',
        ]);
        if ($request->image) { 
            $img = 'data:image/jpeg;base64,'.$request->image;
            $path = 'notification-image/';
            $image = $this->createImage($img,$path);
            $Notification->update([
                'image' => $image
            ]);
            $url = url($image);
        }
       
         //firebase token
        $firebaseToken =UserDeviceToken::whereNotNull('device_id')
                    ->distinct('device_id')
                    ->pluck('device_id')
                    ->all();
        $SERVER_API_KEY = env('NOTIFICATION_SERVER_KEY');
        $firbse = $this->notifications($firebaseToken,$SERVER_API_KEY,$request->title,$request->body,$url);
        return response()->json([ 'status'=> true ,'message' => "success" ,'data'=>$firbse], 200); 
            }
            

        }else{
            return response()->json([ 'status'=> False ,'message' => "No subscribe user",'data'=>null], 200);
        }

        
    }


    public function notifications($firebaseToken,$SERVER_API_KEY,$title,$body,$url){
        
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "image" =>$url,
            ]
        ];
        $dataString = json_encode($data);
    

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        //dd($response);
    }
     
    public function createImage($img, $folderPath, $key = 0)
    {
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . "_" . $key . 'gallery_image.' . $image_type;
        file_put_contents($file, $image_base64);
        return $file;
    }

    public function save_cerificate_subscription(Request $request){

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $user_id = $user->id;
        }

        
        if (empty($user_id)) {
            return response()->json(['status' => false, 'message' => "User not Found"], 200);
        }

        $validator = Validator::make($request->all(), [
            'cate_id' => 'required|exists:job_category,id',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => null], 200);
        }
        $parameters = $request->all();
        extract($parameters);

        $user_cate = [
            'user_id'=>$user_id,
            'cate_id'=>$cate_id,
        ];
        if($request->status == 'true'){    
            $save_d = CategorySubscription::updateOrCreate(['user_id' => $user_id,'cate_id'=>$cate_id],$user_cate);
            return response()->json(['status' => true, 'message' => 'Save Certificate Subscription', 'data' => $user_cate], 200);
        }else{
            $save_d = CategorySubscription::where('user_id',$user_id)->where('cate_id',$cate_id)->delete();
            return response()->json(['status' => true, 'message' => 'Update Certificate Subscription', 'data' => $user_cate], 200);
        }

    }
}



