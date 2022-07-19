<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\UserAvailability;
use App\Models\States;
use Twilio\Rest\Client;
use Validator;
use Str;
use App\Models\Mails;
use App\Models\Expense;
use App\Models\SocialAccount;
use App\Models\UserDeviceToken;
use App\Mail\Signup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailtemp;
use DB;
use Gate;

class UserApiController extends Controller
{
    // User Login
    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => null], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // 
            $user = Auth::user();
            if ($user->is_otp_verified) {
                // 
                $token = auth()->user()->createToken('API Token')->accessToken;
                if (isset($fcm_token)) {
                    UserDeviceToken::create([
                        'user_id' => $user->id,
                        'device_id' => $fcm_token
                    ]);
                }
                $user->profile_image = !empty($user->profile_image) ? url($user->profile_image) : url('profile-image/demo.png');
                $user->token = $token;
                return response()->json(['status' => true, 'message' => "Your account logged in successfully", 'user' => $user], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Email id not registered.', 'user' => null], 200);
            }
        } else {

            return response()->json(['status' => false, 'message' => 'These credentials do not match our records', 'user' => null], 200);
        }
    }

    public function userdetails()
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }

        $user_id = $user->id;
        $data = User::where('id', '=', $user_id)->first();
        if (!empty($data)) {
            $data->profile_image = !empty($user->profile_image) ? url($user->profile_image) : "";
            return response()->json(['status' => true, 'message' => "success", 'user' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => "unsuccess"], 200);
        }
    }


    public function edituserdetails(Request $request)
    {

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }

        $userid = $user->id;
        if (empty($userid)) {
            return response()->json(['status' => false, 'message' => "user not found"], 200);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'dob' => 'required|before:today|date',
            'profile' => 'required|mimes:jpg,jpeg,bmp,png,pdf',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }
        $parameters = $request->all();
        extract($parameters);

        $usemail = User::where([["email", $email], ['id', '!=', $userid]])->first();
        $usphone = User::where([["phone", $phone], ['id', '!=', $userid]])->first();
        if (!empty($usemail)) {
            return response()->json(['status' => false, 'message' => "This email is registerd for another account",'data'=>null], 200);
        }

        if (!empty($usphone)) {
            return response()->json(['status' => false, 'message' => "This Number is registerd for another account",'data'=>null], 200);
        }

        $update = [
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'dob' => $dob,
        ];

        if (!empty($profile)) {
            $img = 'data:image/jpeg;base64,' . $profile;
            $path = 'profile-image/';
            $image = $this->createImage($img, $path);
            $update['profile_image'] = $image;
        }

        User::where('id', $userid)->update($update);
        return response()->json(['status' => true, 'message' => "Update User Details Successfully",'data'=>$update], 200);
    }


    public function userRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'phone' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $user = User::where("email", $email)->first();
        if ($user) {
            // 
            if ($user->is_otp_verified) {
                return response()->json(['status' => false, 'message' => 'Your account with this email already registerd.'], 200);
            }
        } else {
            $user = new User;
        }

        if ($password) {
            $pass = Hash::make($password);
            $user->password = $pass;
        }

        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->dob = $dob;
        $user->address = $address;
        $user->city = $city;
        $user->state = $state;
        $user->zip = $zip;
        $user->gender = $gender;

        $user->save();

        $success['token'] =  $user->createToken('API Token')->accessToken;
        $user->roles()->sync(6);

        //save device token
        if (!empty($fcm_token)) {
            $existtoken = UserDeviceToken::where('device_id', $fcm_token)->first();
            if (!empty($existtoken)) {
                UserDeviceToken::create([
                    'user_id' => $user->id,
                    'device_id' => $fcm_token
                ]);
            }
        }

        return response()->json(['status' => true, 'message' => "Step one done!", 'data' => ['user_id' => $user->id]], 200);
    }

    public function userRegisterStepTwo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            // BSIS Guard Card
            'guard_card' => 'required',
            'guard_card_number' => 'required_if:guard_card,yes',
            'bsis_guard_card_certificate' => 'required_if:guard_card,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // BSIS Exposed Firearms License
            'firearm_license' => 'required',
            'fire_lice_number' => 'required_if:firearm_license,yes',
            'bsis_exposed_certificate' => 'required_if:firearm_license,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // CA CCW
            'ca_ccw' => 'required',
            'ca_ccw_number' => 'required_if:ca_ccw,yes',
            'ca_ccw_certificate' => 'required_if:ca_ccw,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // HR218
            'hr_cert' => 'required',
            'hr_agency' => 'required_if:hr_cert,yes',
            'hr_certificate' => 'required_if:hr_cert,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // Additional firearms license
            'any_other_license' => 'required',
            'medical_training' => 'required',
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $user = User::where("id", $user_id)->first();
        if (!$user) {
            // 
            return response()->json(['status' => false, 'message' => 'User id not valid!'], 200);
        }

        $path = 'certificate/' . $user_id . '/';
        $usermeta = [
            'guard_card'                    => isset($guard_card) ? $guard_card : 'no',
            'guard_card_number'             => isset($guard_card_number) ? $guard_card_number : '',
            'firearm_license'               => isset($firearm_license) ? $firearm_license : 'no',
            'fire_lice_number'              => isset($fire_lice_number) ? $fire_lice_number : '',
            'ca_ccw'                        => isset($ca_ccw) ? $ca_ccw : 'no',
            'ca_ccw_number'                 => isset($ca_ccw_number) ? $ca_ccw_number : '',
            'hr_cert'                       => isset($hr_cert) ? $hr_cert : 'no',
            'hr_agency'                     => isset($hr_agency) ? $hr_agency : '',
            'any_other_license'             => isset($any_other_license) ? $any_other_license : '',

            'bsis_guard_card_certificate'   => isset($bsis_guard_card_certificate) ? $this->uploadDocuments($bsis_guard_card_certificate, $path) : '',
            'bsis_exposed_certificate'      => isset($bsis_exposed_certificate) ? $this->uploadDocuments($bsis_exposed_certificate, $path) : '',
            'ca_ccw_certificate'            => isset($ca_ccw_certificate) ? $this->uploadDocuments($ca_ccw_certificate, $path) : '',
            'hr_certificate'                => isset($hr_certificate) ? $this->uploadDocuments($hr_certificate, $path) : '',
            'medical_training'              => isset($medical_training) ? $medical_training : '',
            'medical_training_certificate'  => isset($medical_training_certificate) ? $this->uploadDocuments($medical_training_certificate, $path) : '',
        ];

        $this->updateUserAllMeta($user_id, $usermeta);

        return response()->json(['status' => true, 'message' => "Step two done!", 'data' => ['user_id' => $user->id]], 200);
    }

    public function userRegisterStepThree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'experience' => 'required',
            'investigation_experience' => 'required',
            'driving_course_date' => 'required',

        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $user = User::where("id", $user_id)->first();
        if (!$user) {
            // 
            return response()->json(['status' => false, 'message' => 'User id not valid!'], 200);
        }

        $path = 'certificate/' . $user_id . '/';
        $usermeta = [
            'experience'                    => isset($experience) ? $experience : '',
            'investigation_experience'      => isset($investigation_experience) ? $investigation_experience : '',
            'driving_course_date'           => isset($driving_course_date) ? $driving_course_date : '',
            'driving_course_date'           => isset($driving_course_date) ? $driving_course_date : '',
            'vaccinated'                    => isset($vaccinated) ? $vaccinated : '',
            // 'medical_training_certificate'  => isset($medical_training_certificate) ? $this->uploadDocuments($medical_training_certificate, $path) : '',
        ];

        $user->discription = isset($user_bio) ? $user_bio : '';
        $user->save();

        $this->updateUserAllMeta($user_id, $usermeta);

        $mail_data = Mails::where('msg_category', 'signup')->first();

        $otp = rand(1000, 9999);
        $config = [
            'from_email' => $mail_data->from_email,
            "reply_email" => $mail_data->reply_email,
            'subject' => $mail_data->subject,
            'name' => $mail_data->name,
            'message' => str_replace('{otp}', $otp, $mail_data->message),
        ];

        Mail::to($user->email)->send(new Mailtemp($config));

        return response()->json(['status' => true, 'message' => "Step three done!", 'data' => ['user_id' => $user->id, 'otp' => $otp]], 200);
    }

    public function userUpdate(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        } 

        $user_id = $user->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $user = User::where("id", $user_id)->first();
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->dob = $dob;
        $user->address = $address;
        $user->city = $city;
        $user->state = $state;
        $user->zip = $zip;
        $user->gender = $gender;

        $user->update();

        return response()->json(['status' => true, 'message' => "Update Step one done!", 'data' => ['user' => $user]], 200);
    }

    public function userUpdateStepTwo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            // BSIS Guard Card
            'guard_card' => 'required',
            'guard_card_number' => 'required_if:guard_card,yes',
            'bsis_guard_card_certificate' => 'required_if:guard_card,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // BSIS Exposed Firearms License
            'firearm_license' => 'required',
            'fire_lice_number' => 'required_if:firearm_license,yes',
            'bsis_exposed_certificate' => 'required_if:firearm_license,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // CA CCW
            'ca_ccw' => 'required',
            'ca_ccw_number' => 'required_if:ca_ccw,yes',
            'ca_ccw_certificate' => 'required_if:ca_ccw,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // HR218
            'hr_cert' => 'required',
            'hr_agency' => 'required_if:hr_cert,yes',
            'hr_certificate' => 'required_if:hr_cert,yes|mimes:jpg,jpeg,bmp,png,pdf',
            // Additional firearms license
            'any_other_license' => 'required',
            'medical_training' => 'required',
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $user = User::where("id", $user_id)->first();
        if (!$user) {
            // 
            return response()->json(['status' => false, 'message' => 'User id not valid!'], 200);
        }

        $path = 'certificate/' . $user_id . '/';
        $usermeta = [
            'guard_card'                    => isset($guard_card) ? $guard_card : 'no',
            'guard_card_number'             => isset($guard_card_number) ? $guard_card_number : '',
            'firearm_license'               => isset($firearm_license) ? $firearm_license : 'no',
            'fire_lice_number'              => isset($fire_lice_number) ? $fire_lice_number : '',
            'ca_ccw'                        => isset($ca_ccw) ? $ca_ccw : 'no',
            'ca_ccw_number'                 => isset($ca_ccw_number) ? $ca_ccw_number : '',
            'hr_cert'                       => isset($hr_cert) ? $hr_cert : 'no',
            'hr_agency'                     => isset($hr_agency) ? $hr_agency : '',
            'any_other_license'             => isset($any_other_license) ? $any_other_license : '',

            'bsis_guard_card_certificate'   => isset($bsis_guard_card_certificate) ? $this->uploadDocuments($bsis_guard_card_certificate, $path) : '',
            'bsis_exposed_certificate'      => isset($bsis_exposed_certificate) ? $this->uploadDocuments($bsis_exposed_certificate, $path) : '',
            'ca_ccw_certificate'            => isset($ca_ccw_certificate) ? $this->uploadDocuments($ca_ccw_certificate, $path) : '',
            'hr_certificate'                => isset($hr_certificate) ? $this->uploadDocuments($hr_certificate, $path) : '',
            'medical_training'              => isset($medical_training) ? $medical_training : '',
            'medical_training_certificate'  => isset($medical_training_certificate) ? $this->uploadDocuments($medical_training_certificate, $path) : '',
        ];

        $this->updateUserAllMeta($user_id, $usermeta);

        return response()->json(['status' => true, 'message' => "Update Step two done!", 'data' => ['user_id'=>$user_id,'usermeta' => $usermeta]], 200);
    }

    public function userUpdateStepThree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'experience' => 'required',
            'investigation_experience' => 'required|numeric',
            'driving_course_date' => 'required|date',

        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $user = User::where("id", $user_id)->first();
        if (!$user) {
            // 
            return response()->json(['status' => false, 'message' => 'User id not valid!'], 200);
        }

        $path = 'certificate/' . $user_id . '/';
        $usermeta = [
            'experience'                    => isset($experience) ? $experience : '',
            'investigation_experience'      => isset($investigation_experience) ? $investigation_experience : '',
            'driving_course_date'           => isset($driving_course_date) ? $driving_course_date : '',
            'driving_course_date'           => isset($driving_course_date) ? $driving_course_date : '',
            'vaccinated'                    => isset($vaccinated) ? $vaccinated : '',
            // 'medical_training_certificate'  => isset($medical_training_certificate) ? $this->uploadDocuments($medical_training_certificate, $path) : '',
        ];

        $user->discription = isset($user_bio) ? $user_bio : '';
        $user->update();

        $this->updateUserAllMeta($user_id, $usermeta);

        return response()->json(['status' => true, 'message' => "Update Step three done!", 'data' => ['user_id'=>$user_id,'usermeta' => $usermeta]], 200);
    }

    public function resendOTP(Request $request)
    {
        try {

            $sid = env("TWILIO_SID");
            $token = env("TWILIO_TOKEN");
            $from = env("TWILIO_FROM");
            $service = env("TWILIO_SERVICE");

            $twilio = new Client($sid, $token);

            $verification = $twilio->verify->v2->services($service)
                ->verifications
                ->create(
                    $request->phone,
                    "sms",
                );

            return response()->json([
                'status' => true, 'message' => " OTP successfully send to user registered mobile number"
            ], 200);
        } catch (\Twilio\Exceptions\RestException $exception) {

            return response()->json([
                'status' => false, 'message' => "Enterd mobile number is invalid"
            ], 200);
        }
    }

    public function sendOTP($phone)
    {
        try {
            $sid = env("TWILIO_SID");
            $token = env("TWILIO_TOKEN");
            $from = env("TWILIO_FROM");
            $service = env("TWILIO_SERVICE");
            $twilio = new Client($sid, $token);
            $verification = $twilio->verify->v2->services($service)
                ->verifications
                ->create(
                    $phone,
                    "sms",
                );

            return response()->json([
                'status' => true, 'message' => " OTP successfully send to user registered mobile number"
            ], 200);
        } catch (\Twilio\Exceptions\RestException $exception) {

            return response()->json([
                'status' => false, 'message' => "Enterd mobile number is invalid"
            ], 200);
        }
    }

    public function verifyOTP(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        try {

            $sid = env("TWILIO_SID");
            $token = env("TWILIO_TOKEN");
            $from = env("TWILIO_FROM");
            $service = env("TWILIO_SERVICE");


            $twilio = new Client($sid, $token);


            $verification_check = $twilio->verify->v2->services($service)
                ->verificationChecks
                ->create(
                    $req->otp, // code
                    ["to" => $req->phone],
                );
            // dd($verification_check->status);
            // exit;


            if ($verification_check->status == 'approved') {

                $user = User::where('phone', $req->phone)->update([
                    'is_mobile_verified' => 1
                ]);

                return response()->json([
                    'status' => true,
                    'message' => " Your mobile number verified successfully"
                ], 200);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => " Invalid OTP, Please enter valid OTP or Resend OTP"
                ], 200);
            }
        } catch (\Twilio\Exceptions\RestException $e) {

            return response()->json([
                'status' => false,
                'message' => " Invalid OTP, Please enter valid OTP or Resend OTP"
            ], 200);
        }
    }

    public function social(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'provider' => 'in:google,facebook',
                'access_token' => 'required',
            ]);

            $social_user = Socialite::driver($request->provider)->stateless()->userFromToken($request->input('access_token'));
            if (!$social_user) {
                throw new Error(Str::replaceArray('?', [$request->provider], __('messages.invalid_social')));
            }

            $token = Str::random(80);
            $account = SocialAccount::where("provider_user_id", $social_user->getId())
                ->where("provider", $request->provider)
                ->with('user')->first();
            if ($account) {
                $user = User::where(["id" => $account->user->id])->first();
                $user->api_token = hash('sha256', $token);
                $user->device_id = $request->input('device_id') ? $request->input('device_id') : "";
                $user->device_token = $request->input('device_token') ? $request->input('device_token') : "";
                $user->save();
                $data = new \stdClass();
                $data->token = $user->createToken(env('APP_NAME'))->accessToken;
                return response()->json(['data' => $data, 'status' => true, 'message' => 'verify_success', 'details' => $user]);
            } else {
                $fname = $social_user->getName() ? $social_user->getName() : "";
                $lname = $social_user->getNickname() ? $social_user->getNickname() : "";
                $loginEmail = $social_user->getEmail() ? $social_user->getEmail() : $social_user->getId() . '@' . $request->provider . '.com';
                $loginName =  $fname . $lname;
                // create new user and social login if user with social id not found.
                $user = User::where("email", $loginEmail)->first();
                if (!$user) {
                    $user = new User;
                    $user->email = $loginEmail;
                    $user->first_name = $loginName;
                    $user->social_id = $social_user->getId();
                    $user->password = Hash::make('social');
                    $user->api_token = hash('sha256', $token);
                    $user->device_id = $request->input('device_id') ? $request->input('device_id') : "";
                    $user->device_token = $request->input('device_token') ? $request->input('device_token') : "";
                    $user->save();
                }

                $social_account = new SocialAccount;
                $social_account->provider = $request->provider;
                $social_account->provider_user_id = $social_user->getId();
                $social_account->user_id = $user->id;
                $social_account->save();
                $data = new \stdClass();
                $data->token = $user->createToken(env('APP_NAME'))->accessToken;
                return response()->json(['data' => $data, 'status' => true, 'message' => 'verify_success', 'details' => $user]);
            }
        } catch (\Thuserowable $th) {
            return response()->json([
                "message" => $th->getMessage(),
            ], 400);
        }
    }



    public function changePassword(Request $request)
    {
        $user = '';
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!']);
        }

        $validator = Validator::make($request->all(), [
            'old_password'  => 'required',
            'new_password'   => 'required|min:6',
            'confirm_password'   => 'required|min:6|same:new_password',
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $user_id = $user->id;

        $parameters = $request->all();
        extract($parameters);

        $pass =  Hash::make($request->new_password);

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = $pass;

            $user->save();
        }

        return response()->json(['status' => true, 'message' => "Your password changed successfully"], 200);
    }


    public function sendforgetotp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'           => 'required|exists:users'
        ]);
        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }
        $user = User::where('email', '=', $request->email)->first();
        $mail_data = Mails::where('msg_category', 'password reset')->first();

        $otp = rand(1000, 9999);
        $config = [
            'from_email' => $mail_data->from_email,
            "reply_email" => $mail_data->reply_email,
            'subject' => $mail_data->subject,
            'name' => $mail_data->name,
            'message' => str_replace('{otp}', $otp, $mail_data->message),
        ];

        Mail::to($user->email)->send(new Mailtemp($config));
        return response()->json(['status' => true, 'message' => "Fogot Password Otp Sent!", 'data' => ['user_id' => $user->id, 'otp' => $otp]], 200);
    }

    public function createNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'  =>  'required|min:6|max:20',
            'email'  =>  'required|email',
            'confirm_password'   => 'required|min:6|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        } else {
            $data = User::where('email', $request->email)->first();
            if (!empty($data)) {
                $data->password = Hash::make($request->password);
                $data->save();
                return response()->json(['status' => true, 'message' => 'Your Password has been changed successfully'], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'User not found'], 200);
            }
        }
    }

    public function registrationOTPVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  =>  'required',
            // 'otp' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        } else {
            $user = User::where('id', $request->user_id)->first();
            if (!empty($user)) {
                $user->is_otp_verified = true;
                $user->save();
                return response()->json(['status' => true, 'message' => 'Your have register successfully'], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Faild to register your account!'], 200);
            }
        }
    }


    public function verifyforgetotp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp'               => 'required',
            'user_id'           => 'required'
        ]);
        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }
        $userotp = ForgetOtp::where('otp', '=', $request->otp)->where('user_id', '=', $request->user_id)->first();
        if (!empty($userotp)) {




            return response()->json(['status' => true, 'message' => "OTP verified"], 200);
        } else {




            return response()->json(['status' => false, 'message' => "Invalid otp"], 200);
        }
    }




    public function forgetpassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile_email'                 => 'required',
            'password'            => 'required'
        ]);
        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }
        $user = User::where('email', '=', $request->mobile_email)
            ->orWhere('phone', '=', $request->mobile_email)
            ->first();
        $hash = Hash::make($request->password);
        if (!empty($user)) {
            if ($user->active_status ==  'active') {
                User::where('id', '=', $user->id)
                    ->update(['password' => $hash]);
                return response()->json(['status' => true, 'message' => "Your Password Updated Successfully", 'user' => $user], 200);
            } else {
                return response()->json(['status' => false, 'message' => "The email has already been registred. currently in inactive mode.to active your account mail on  customerservices@office-share.io", 'user' => Null], 200);
            }
        } else {
            return response()->json(['status' => true, 'message' => "user not found", 'user' => Null], 200);
        }
    }

    public function myaccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        $user = User::where('id', '=', $request->user_id)->first();
        if (!empty($user)) {
            return response()->json(['status' => true, 'message' => "success", 'user' => $user], 200);
        } else {
            return response()->json(['status' => false, 'message' => "success", 'user' => Null], 200);
        }
    }
    public function logout(Request $request)
    {
        $user = User::where('id', '=', $request->user_id)->first();
        if (!empty($user)) {
            $location = DB::table('user_device_token')->where('user_id', '=', $request->user_id)->where('device_token', '=', $request->firebase_token)->where('device_id', '=', $request->device_id)->delete();
            return response()->json(['status'  => true, 'message' => 'Your account logout successfully']);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'user not found'
            ]);
        }
    }
    public function  userforgot_bk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'token' => '']);
        } else {

            $email = $request->email;
            $userData  = User::where('email', $email)->get()->first();
            if (!empty($userData)) {
                $token = Str::random(100);
                $name = $userData->first_name;
                $userData->remember_token =  $token;
                $userData->save();
                $mail_data = Mails::where('msg_category', 'Password reset')->first();
                $url = url('/userresetpassword') . '/' . $token;
                if (!empty($email)) {
                    $details = ['email' => $email, 'url' => $url, 'first_name' => $name];
                    $message = str_replace('{{reset_password}}', $url, $mail_data->message);
                    $config = [
                        'from_email' => $mail_data->from_email,
                        "reply_email" => $mail_data->reply_email,
                        'subject' => $mail_data->subject,
                        'name' => $mail_data->name,
                        'message' => $message,
                    ];

                    Mail::to('gunjanmanghnani5@gmail.com')->send(new Mailtemp($config, $details, function ($message) use ($details) {
                        $message->to($details['email'])->subject('Reset Password')->from(env('MAIL_FROM_ADDRESS'));
                    }));
                }
                return response()->json(['token' => $token, 'status' => true, 'message' => 'Reset Password Link Send Your Email', 'url' => $url]);
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid Email']);
            }
        }
    }

    public function userforgototp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'token' => '']);
        } else {

            $phone = $request->phone;
            $userData  = User::where('phone', $phone)->first();
            if (!empty($userData)) {

                try {

                    $sid = env("TWILIO_SID");
                    $token = env("TWILIO_TOKEN");
                    $from = env("TWILIO_FROM");
                    $service = env("TWILIO_SERVICE");

                    $twilio = new Client($sid, $token);

                    $verification = $twilio->verify->v2->services($service)
                        ->verifications
                        ->create(
                            $request->phone,
                            "sms",
                        );

                    return response()->json([
                        'status' => true, 'message' => " OTP successfully send to user registered mobile number"
                    ], 200);
                } catch (\Twilio\Exceptions\RestException $exception) {

                    return response()->json([
                        'status' => false, 'message' => "Enterd mobile number is invalid"
                    ], 200);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid User']);
            }
        }
    }

    public function verifyforgototp(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'phone' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        try {

            $sid = env("TWILIO_SID");
            $token = env("TWILIO_TOKEN");
            $from = env("TWILIO_FROM");
            $service = env("TWILIO_SERVICE");


            $twilio = new Client($sid, $token);


            $verification_check = $twilio->verify->v2->services($service)
                ->verificationChecks
                ->create(
                    $req->otp, // code
                    ["to" => $req->phone],
                );
            // dd($verification_check->status);
            // exit;


            if ($verification_check->status == 'approved') {

                return response()->json([
                    'status' => true,
                    'message' => " Your mobile number verified successfully"
                ], 200);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => " Invalid OTP, Please enter valid OTP or Resend OTP"
                ], 200);
            }
        } catch (\Twilio\Exceptions\RestException $e) {

            return response()->json([
                'status' => false,
                'message' => " Invalid OTP, Please enter valid OTP or Resend OTP"
            ], 200);
        }
    }


    public function userchangepassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password'  =>  'required|min:8|max:20',
            'mobile'  =>  'required|min:8|max:20',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        } else {
            $data = User::where('phone', $request->mobile)->first();
            if (!empty($data)) {
                $data->password = Hash::make($request->password);
                $data->save();
                return response()->json(['status' => true, 'message' => 'Your Password has been changed successfully']);
            } else {
                return response()->json(['status' => false, 'message' => 'User not found']);
            }
        }
    }

    public function createImage($img, $folderPath, $key = 0)
    {
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . "_" . $key . 'user_image.' . $image_type;
        file_put_contents($file, $image_base64);
        return $file;
    }

    public function stateList()
    {
        $data = States::where('country_id',231)->get();
        if(!empty($data)){
            foreach($data as $state){
                $state_data[]=[
                    'id' =>$state->id,
                    'name'    =>$state->name,
                ];
            }
        }
        return response()->json(['status' => true, 'message' => "All State List", 'data' => $state_data], 200);
    }

    public function availableUser(Request $request)
    {
        $user = '';
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!','data' => []]);
        }
        $user_id = $user->id;

        $parameters = $request->all();
        extract($parameters);

        if(!empty($user_id)){

            $data = UserAvailability::where('user_id',$user_id)->first();
            $is_available = $data->is_available;
            $week = [
                [
                    'day' => 'mon',
                    'start_time' => $data->mon_open,
                    'end_time' => $data->mon_close,
                ],
                [  
                    'day' => 'tue',
                    'start_time' => $data->tue_open,
                    'end_time' => $data->tue_close,
                ],
                [   'day' => 'wed',
                    'start_time' => $data->wed_open,
                    'end_time' => $data->wed_close,
                ],
                [   'day' => 'thu',
                   'start_time' => $data->thu_open,
                   'end_time' => $data->thu_close,
                ],
                [   'day' => 'fri',
                    'start_time' => $data->fri_open,
                    'end_time' => $data->fri_close,
                ],
                [   'day' => 'sat',
                    'start_time' => $data->sat_open,
                    'end_time' => $data->sat_close,
                ],
                [   'day' => 'sun',
                    'start_time' => $data->sun_open,
                    'end_time' => $data->sun_close,
                ]
            ];
            return response()->json(['status' => true, 'message' => "Available User",'data'=>['is_available'=>$is_available, 'week_data'=>$week]], 200);
        }else{

            return response()->json(['status' => false, 'message' => "Not Available",'data'=>[]], 200);
        }
    }
    public function UpdateavailableUser(Request $request)
    {
        $user = '';
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!']);
        }

        $user_id = $user->id;
        $validator = Validator::make($request->all(), [
            'is_available' => 'required|numeric',

            'mon_open' => 'required_if:is_available,1|date_format:H:i',
            'mon_close' => 'required_if:is_available,1|after:mon_open|date_format:H:i',

            'tue_open' => 'required_if:is_available,1|date_format:H:i',
            'tue_close' => 'required_if:is_available,1|after:tue_open|date_format:H:i',

            'wed_open' => 'required_if:is_available,1|date_format:H:i',
            'wed_close' => 'required_if:is_available,1|after:wed_open|date_format:H:i',

            'thu_open' => 'required_if:is_available,1|date_format:H:i',
            'thu_close' => 'required_if:is_available,1|after:thu_open|date_format:H:i',

            'fri_open' => 'required_if:is_available,1|date_format:H:i',
            'fri_close' => 'required_if:is_available,1|after:fri_open|date_format:H:i',

            'sat_open' => 'required_if:is_available,1|date_format:H:i',
            'sat_close' => 'required_if:is_available,1|after:sat_open|date_format:H:i',

            'sun_open' => 'required_if:is_available,1|date_format:H:i',
            'sun_close' => 'required_if:is_available,1|after:sun_open|date_format:H:i',
        ]);
        if($request->is_available != 0 && $request->is_available != 1){
            return response()->json(['status' => false, 'message' => 'Invalid Format select 0 or 1'], 200);
        }
        if($request->is_available != 1 && $request->mon_open != null && $request->mon_close != null){
            return response()->json(['status' => false, 'message' => 'user not available'], 200);
        }
        if($request->is_available != 1 && $request->tue_open != null && $request->tue_close != null){
            return response()->json(['status' => false, 'message' => 'user not available'], 200);
        }
        if($request->is_available != 1 && $request->wed_open != null && $request->wed_close != null){
            return response()->json(['status' => false, 'message' => 'user not available'], 200);
        }
        if($request->is_available != 1 && $request->thu_open != null && $request->thu_close != null){
            return response()->json(['status' => false, 'message' => 'user not available'], 200);
        }
        if($request->is_available != 1 && $request->fri_open != null && $request->fri_close != null){
            return response()->json(['status' => false, 'message' => 'user not available'], 200);
        }
        if($request->is_available != 1 && $request->sat_open != null && $request->sat_close != null){
            return response()->json(['status' => false, 'message' => 'user not available'], 200);
        }
        if($request->is_available != 1 && $request->sun_open != null && $request->sun_close != null){
            return response()->json(['status' => false, 'message' => 'user not available'], 200);
        }
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        if(!empty($user_id)){
            $data = [
            'user_id'  => $user_id,
            'mon_open'  => isset($mon_open) ? $mon_open : null,
            'mon_close'  => isset($mon_close) ? $mon_close : null,
            'tue_open'  => isset($tue_open) ? $tue_open : null,
            'tue_close'  => isset($tue_close) ? $tue_close : null,
            'wed_open'  => isset($wed_open) ? $wed_open : null,
            'wed_close'  => isset($wed_close) ? $wed_close : null,
            'thu_open'  => isset($thu_open) ? $thu_open : null,
            'thu_close'  => isset($thu_close) ? $thu_close : null,
            'fri_open'  => isset($fri_open) ? $fri_open : null,
            'fri_close'  => isset($fri_close) ? $fri_close : null,
            'sat_open'  => isset($sat_open) ? $sat_open : null,
            'sat_close'  => isset($sat_close) ? $sat_close : null,
            'sun_open'  => isset($sun_open) ? $sun_open : null,
            'sun_close'  => isset($sun_close) ? $sun_close : null,
            'is_available'  => $is_available,
            ];
            $update_availability = UserAvailability::where('user_id',$user_id)->update($data);
            return response()->json(['status' => true, 'message' => "User Availability"], 200);
        }else{

            return response()->json(['status' => false, 'message' => "No Availability"], 200);
        }
        
    }
    public function addUserExpense(Request $request){

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }

        $user_id = $user->id;
        if (empty($user_id)) {
            return response()->json(['status' => false, 'message' => "User not Found"], 200);
        }

        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'title' => 'required',
            'image' => 'required|mimes:jpeg,bmp,png,gif,svg,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => null], 200);
        }
        $parameters = $request->all();
        extract($parameters);

        // $job_list = DB::table('job_applied')
        //             ->join('users', 'users.id', '=', 'job_applied.user_id')
        //             ->join('jobs', 'jobs.id', '=', 'job_applied.job_id')
        //             ->where('job_applied.user_id',$user_id)
        //             ->where('job_applied.status','assigned')
        //             ->select('jobs.*')
        //             ->get();
        //             if(count($job_list) > 0){
        //                 foreach($job_list as $job_data){
        //                     $job_expense[] = [
        //                         'title' => $job_data->title,
        //                         'id' => $job_data->id,
        //                     ];
        //                 }
        //             }else{
        //                 $job_expense = "No assigned Job";
        //             }
                    
        $expense_data = Expense::Create([
            'user_id'=>$user_id,
            'job_id'=>$job_id,
            'title'=>$title,
        ]);
        if(!empty($image))
        {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('expense/', $filename);
            $expense_data['image'] = 'expense/'.$filename;
        }
        $expense_data->update();
        if(!empty($expense_data)){
            return response()->json(['status' => true, 'message' => 'Expense upload successfully','data' => $expense_data], 200);
        }else{
            return response()->json(['status' => false, 'message' => 'No Expense', 'job_title'=> null, 'data' => null], 200);
        }

    }

    public function userExpense(Request $request){

        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }

        $user_id = $user->id;
        if (empty($user_id)) {
            return response()->json(['status' => false, 'message' => "User not Found"], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        // $all_expense_data = Expense::where('user_id', $user_id)->get();

        $all_expense_data = DB::table('expense')
                    ->join('jobs', 'jobs.id', '=', 'expense.job_id')
                    ->where('expense.user_id',$user_id)
                    ->select('expense.*','jobs.*','jobs.id as job_auto_id','expense.title as expense_title','expense.image as expense_img','expense.created_at as created_expense_date')
                    ->get();
        if(count($all_expense_data) > 0){
            foreach($all_expense_data as $exp_value){
                $img_download_url = url('/'.$exp_value->expense_img);
                $expense_user_data[] = [
                    'job_title' => $exp_value->title,
                    'expense_title' => $exp_value->expense_title,
                    'created_date' => date('M d,y', strtotime($exp_value->created_expense_date)),
                    'download_link'=> $img_download_url,
                ];
            }
            return response()->json(['status' => true, 'message' => 'All Expenses List', 'data' => $expense_user_data], 200);
        }else{
            return response()->json(['status' => false, 'message' => 'No Expense', 'data' => null], 200);
        }

    }
}
