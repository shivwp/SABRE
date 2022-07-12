@extends('layouts.vertical-menu.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/ion.rangeSlider/css/ion.rangeSlider.skinSimple.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/date-picker/spectrum.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/multipleselect/multiple-select.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />
<style type="text/css">
    a#money_reason {
    color: #fff !important;
    text-align: center;
    font-weight: 700;
    background-color: darkgreen;
}
.modal-body sup{
    color: red;
}
label.form-check-label.weekdy {
    font-size: 16px;
    font-weight: 600;
}
div#m_dcontent4 {
    display: flex;
    align-items: center;
}
.txt label{
    font-size: 14px;
    font-weight: 500;
}
div#m_dcontent {
    display: flex;
    align-items: center;
}
</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">{{$title}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.users.index') }}">user</a></li>
                 @if(isset($user->id))
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                @endif
            </ol>
        </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
<!-- ROW-1 OPEN-->
<div class="card">
    <div class="alert alert-success" role="alert" id="successMsg" style="display: none;margin: 20px 20px 0px 20px;" >
      Money Added Successfully
    </div>
    <form  method="post" action="{{route('dashboard.users.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <input type="hidden" name="id" value="{{ old('id', isset($user) ? $user->id : '') }}">
            <h3>Personal info</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ old('first_name', isset($user) ? $user->first_name : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ old('last_name', isset($user) ? $user->last_name : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Last Name" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" value="" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob"  value="{{ old('dob', isset($user) ? $user->dob : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Select gender</option>
                            <option value="male" {{isset($user) && ($user->gender == "male") ? "selected" : ''}}>Male</option>
                            <option value="female"{{isset($user) && ($user->gender == "female") ? "selected" : ''}}>Female</option>
                            <option value="not_say"{{isset($user) && ($user->gender == "not_say") ? "selected" : ''}}>Not Say</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="number" class="form-control" name="phone"  value="{{ old('phone', isset($user) ? $user->phone : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Home Address</label>
                        <input type="text" class="form-control" name="home_address"  value="{{ old('home_address', isset($user) ? $user->address : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city"  value="{{ old('city', isset($user) ? $user->city : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">US State</label>
                        <select name="state" class="form-control" required>
                            <option value="">select</option>
                            @if(count($states) > 0)
                                @foreach($states as $val)
                                    <option value="{{$val->id}}" {{isset($user->state) && $user->state == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Zip</label>
                        <input type="number" class="form-control" name="zip"  value="{{ old('zip', isset($user) ? $user->zip : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Select Role</label>
                        <select name="role" id="role" class="form-control select2">
                            @foreach($roles as $id => $role)
                                <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <h3>ID Proof</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">BSIS Guard Card</label>
                        <select name="guard_card" class="form-control" required>
                            <option value="">Select Guard Card</option>
                            <option value="yes" {{isset($user_meta['guard_card']) && $user_meta['guard_card'] == "yes" ? 'selected' : ''}}>Yes</option>
                            <option value="no" {{isset($user_meta['guard_card']) && $user_meta['guard_card']  == "no" ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">If Yes, BSIS Guard Card Number</label>
                        <input type="text" class="form-control" name="guard_card_number"  value="{{ old('guard_card_number', isset($user_meta) && !empty($user_meta['guard_card_number']) ? $user_meta['guard_card_number'] : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload your certificate here</label>
                        <input type="file" name="upload_certificate" class="form-control" value="">

                        <input type="hidden" name="upload_certificate_old" value="{{isset($user_meta) && !empty($user_meta['bsis_guard_card_certificate']) ? $user_meta['bsis_guard_card_certificate'] : ''}}">
                    </div>
                </div>
                @if(!empty($user_meta['bsis_guard_card_certificate']))
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        <img src="{{url('certificate/'.$user_meta['bsis_guard_card_certificate'])}}">
                    </div>
                </div>
                @endif
            </div>
                <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">BSIS Exposed Firearms License</label>
                        <select name="firearm_license" class="form-control" required>
                            <option value="">Select BSIS License</option>
                            <option value="yes" {{isset($user_meta['firearm_license']) && $user_meta['firearm_license'] == "yes" ? 'selected' : ''}}>Yes</option>
                            <option value="no" {{isset($user_meta['firearm_license']) && $user_meta['firearm_license']  == "no" ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">If Yes, BSIS Exposed Firearms License Number</label>
                        <input type="text" class="form-control" name="fire_lice_number"  value="{{ old('fire_lice_number', isset($user_meta) && !empty($user_meta['fire_lice_number']) ? $user_meta['fire_lice_number'] : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload your certificate here</label>
                        <input type="file" name="upload_certificate_2" class="form-control" value="">
                        <input type="hidden" name="upload_certificate_2_old" value="{{isset($user_meta) && !empty($user_meta['bsis_exposed_certificate']) ? $user_meta['bsis_exposed_certificate'] : ''}}">
                    </div>
                </div>
                @if(!empty($user_meta['bsis_exposed_certificate']))
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        <img src="{{url('certificate/'.$user_meta['bsis_exposed_certificate'])}}">
                    </div>
                </div>
                @endif
            </div>
                <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">CA CCW</label>
                        <select name="ca_ccw" class="form-control" required>
                            <option value="">Select</option>
                            <option value="yes" {{isset($user_meta['ca_ccw']) && $user_meta['ca_ccw'] == "yes" ? 'selected' : ''}}>Yes</option>
                            <option value="no" {{isset($user_meta['ca_ccw']) && $user_meta['ca_ccw']  == "no" ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">If Yes, CA CCW Issuing Agency and Number</label>
                        <input type="text" class="form-control" name="ca_ccw_number"  value="{{ old('ca_ccw_number', isset($user_meta) && !empty($user_meta['ca_ccw_number']) ? $user_meta['ca_ccw_number'] : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload your certificate here</label>
                        <input type="file" name="upload_certificate_3" class="form-control" value="">
                        <input type="hidden" name="upload_certificate_3_old" value="{{isset($user_meta) && !empty($user_meta['ca_ccw_certificate'] ) ? $user_meta['ca_ccw_certificate'] : ''}}">
                    </div>
                </div>
                @if(!empty($user_meta['ca_ccw_certificate']))
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        <img src="{{url('certificate/'.$user_meta['ca_ccw_certificate'])}}">
                    </div>
                </div>
                @endif
            </div>
                <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">HR 218</label>
                        <select name="hr_cert" class="form-control" required>
                            <option value="">Select</option>
                            <option value="yes" {{isset($user_meta['hr_cert']) && $user_meta['hr_cert'] == "yes" ? 'selected' : ''}}>Yes</option>
                            <option value="no" {{isset($user_meta['hr_cert']) && $user_meta['hr_cert']  == "no" ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">If Yes, HR 218 Issuing Agency </label>
                        <input type="text" class="form-control" name="hr_agency"  value="{{ old('hr_agency', isset($user_meta) && !empty($user_meta['hr_agency']) ? $user_meta['hr_agency'] : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload your certificate here</label>
                        <input type="file" name="upload_certificate_4" class="form-control" value="">
                        <input type="hidden" name="upload_certificate_4_old" value="{{isset($user_meta) && !empty($user_meta['hr_certificate']) ? $user_meta['hr_certificate'] : ''}}">
                    </div>
                </div>
                @if(!empty($user_meta['hr_certificate']))
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        <img src="{{url('certificate/'.$user_meta['hr_certificate'])}}">
                    </div>
                </div>
                @endif
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Do you have any Additional Firearms License ? Please list them all.</label>
                        <textarea name="any_other_license" class="form-control" rows="6">{{ old('any_other_license', isset($user_meta) && !empty($user_meta['any_other_license'])? $user_meta['any_other_license'] : '') }}</textarea>
                    </div>
                    
                </div>
            </div>
            <hr>
            <h3>Bio About</h3> 
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">What Experience do you have</label>
                        <input type="checkbox" name="military" value="military" {{isset($user_meta['military']) && !empty($user_meta['military'] && $user_meta['military'] == 'military') ? "checked" : ''}}>
                        <label for="vehicle1"> Military</label><br>
                        <input type="checkbox" name="police" value="police" {{isset($user_meta['police']) && !empty($user_meta['police'] && $user_meta['police'] == 'police') ? 'checked' : ''}} > 
                        <label for="vehicle2"> Police</label><br>
                        <input type="checkbox" name="close_pro" value="close_pro" {{isset($user_meta['close_pro']) && !empty($user_meta['close_pro'] && $user_meta['close_pro'] == 'close_pro') ? 'checked' : ''}}>
                        <label for="vehicle3"> Close Protection</label><br>
                        <input type="checkbox" name="exe_pro" value="exe_pro" {{isset($user_meta['exe_pro']) && !empty($user_meta['exe_pro'] && $user_meta['exe_pro'] == 'exe_pro') ? 'checked' : ''}}>
                        <label for="vehicle2"> Executive Protection</label><br>
                        <input type="checkbox" name="advance_course" value="advance_course" {{isset($user_meta['advance_course']) && !empty($user_meta['advance_course'] && $user_meta['advance_course'] == 'advance_course') ? 'checked' : ''}}>
                        <label for="vehicle2"> Advanced Driving Course</label><br>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Medical Training</label>
                        <input type="checkbox" name="cpr" value="cpr" {{isset($user_meta['cpr']) && !empty($user_meta['cpr'] && $user_meta['cpr'] == 'cpr') ? 'checked' : ''}}>
                        <label for="vehicle1" > CPR</label><br>
                        <input type="checkbox" name="first_aid" value="first_aid" {{isset($user_meta['first_aid']) && !empty($user_meta['first_aid'] && $user_meta['first_aid'] == 'first_aid') ? 'checked' : ''}}>
                        <label for="vehicle2"> First Aid</label><br>
                        <input type="checkbox" name="trauma" value="trauma" {{isset($user_meta['trauma']) && !empty($user_meta['trauma'] && $user_meta['trauma'] == 'trauma') ? 'checked' : ''}}>
                        <label for="vehicle3"> Trauma Protection</label><br>
                        <input type="checkbox" name="advance_first" value="advance_first" {{isset($user_meta['advance_first']) && !empty($user_meta['advance_first'] && $user_meta['advance_first'] == 'advance_first') ? 'checked' : ''}}>
                        <label for="vehicle2"> Advanced First Aid TCCC</label><br>
                        <input type="checkbox" name="head_shot" value="head_shot" {{isset($user_meta['head_shot']) && !empty($user_meta['head_shot'] && $user_meta['head_shot'] == 'head_shot') ? 'checked' : ''}}>
                        <label for="vehicle2"> Upload Head Shot</label><br>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Investigation Experience</label>
                        <input type="text" class="form-control" name="invest_exp" placeholder="Investigation Experience" value="{{ old('invest_exp', isset($user_meta) && !empty($user_meta['invest_exp']) ? $user_meta['invest_exp'] : '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">When did you last take an advance driving course</label>
                        <input type="date" class="form-control" name="last_drive" placeholder="" value="{{old('last_drive', isset($user_meta) && !empty($user_meta['last_drive']) ? $user_meta['last_drive'] : '')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Upload your medical training certificate here</label>
                        <input type="file" name="upload_certificate_5" class="form-control" value="">
                        <input type="hidden" name="upload_certificate_5_old" value="{{isset($user_meta) && !empty($user_meta['medical_training_certificate']) ? $user_meta['medical_training_certificate'] : ''}}">
                    </div>
                </div>
                @if(!empty($user_meta['medical_training_certificate']))
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        <img src="{{url('certificate/'.$user_meta['medical_training_certificate'])}}">
                    </div>
                </div>
                @endif
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Short Bio of You</label>
                        <textarea class="form-control" name="short_bio" rows="5" required>{{old('short_bio', isset($user) ? $user->discription : '')}}</textarea>
                    </div>
                </div>
            </div>
            <hr>
            <h3>User Availability</h3>
            <div class="date-select ">
                <div class="row days content-wrap sunday-2 ">
                    <div class="col-lg-2 col-sm-2 dayselection" id="m_dcontent">
                        <div class="txt">
                            <div class="form-check">
                                <label class="form-check-label weekdy" for="sunday">
                                Sunday
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent2">
                        <div class="form-group">
                        <p>Opening Time</p> 
                            <input type="time" class="disable_time form-control dayo" name="sun_open" value="{{isset($user_avail->sun_open) ? $user_avail->sun_open : null}}" id="open_time">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                        <div class="form-group">
                        <p>Closing Time</p>  
                            <input type="time" class="disable_time form-control dayc" name="sun_close" value="{{isset($user_avail->sun_close) ? $user_avail->sun_close : null}}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 fullselection" id="m_dcontent4">
                        <div class="txt">
                            <div class="form-check">
                                @if(isset($user_avail) && $user_avail->sun_open == null && $user_avail->sun_close == null)
                                <input class="form-check-input dayfull" name="not_available" type="checkbox"  id="sunday-2" checked>
                                @else
                                <input class="form-check-input dayfull" name="not_available" type="checkbox"  id="sunday-2">
                                @endif
                                <label class="form-check-label " for="sunday-2">
                                Not Available
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row days content-wrap monday-2 ">
                    <div class="col-lg-2 col-sm-2 dayselection" id="m_dcontent">
                        <div class="txt">
                            <div class="form-check">
                                <label class="form-check-label weekdy" for="monday">
                                Monday
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent2">
                        <div class="form-group">
                            <p>Opening Time</p> 
                            <input type="time" class="disable_time form-control dayo" name="mon_open" value="{{isset($user_avail->mon_open) ? $user_avail->mon_open : null }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                        <div class="form-group">
                            <p>Closing Time</p>  
                            <input type="time" class="disable_time form-control dayc" name="mon_close" value="{{isset($user_avail->mon_close) ? $user_avail->mon_close : null }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 fullselection" id="m_dcontent4">
                        <div class="txt">
                            <div class="form-check">
                                @if(isset($user_avail) && $user_avail->mon_open == null && $user_avail->mon_close == null)
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2" checked>
                                @else
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2">
                                @endif
                                <label class="form-check-label " for="sunday-2">
                                Not Available
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row days content-wrap sunday-2 ">
                    <div class="col-lg-2 col-sm-2 dayselection" id="m_dcontent">
                        <div class="txt">
                            <div class="form-check">
                                <label class="form-check-label weekdy" for="sunday">
                                Tuesday
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent2">
                        <div class="form-group">
                        <p>Opening Time</p> 
                            <input type="time" class="disable_time form-control dayo" name="tue_open" value="{{isset($user_avail->tue_open) ? $user_avail->tue_open : null }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                        <div class="form-group">
                        <p>Closing Time</p>  
                            <input type="time" class="disable_time form-control dayc" name="tue_close" value="{{isset($user_avail->tue_close) ? $user_avail->tue_close : null }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 fullselection" id="m_dcontent4">
                        <div class="txt">
                            <div class="form-check">
                                @if(isset($user_avail) && $user_avail->tue_open == null && $user_avail->tue_close == null)
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2" checked>
                                @else
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2">
                                @endif
                                <label class="form-check-label " for="sunday-2">
                                Not Available
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row days content-wrap monday-2 ">
                    <div class="col-lg-2 col-sm-2 dayselection" id="m_dcontent">
                        <div class="txt">
                            <div class="form-check">
                                <label class="form-check-label weekdy" for="monday">
                                Wednesday
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent2">
                        <div class="form-group">
                            <p>Opening Time</p> 
                            <input type="time" class="disable_time form-control dayo" name="wed_open" value="{{isset($user_avail->wed_open) ? $user_avail->wed_open : null }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                        <div class="form-group">
                            <p>Closing Time</p>  
                            <input type="time" class="disable_time form-control dayc" name="wed_close" value="{{isset($user_avail->wed_close) ? $user_avail->wed_close : null }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 fullselection" id="m_dcontent4">
                        <div class="txt">
                            <div class="form-check">
                                @if(isset($user_avail) && $user_avail->wed_open == null && $user_avail->wed_close == null)
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2" checked>
                                @else
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2">
                                @endif
                                <label class="form-check-label " for="sunday-2">
                                Not Available
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row days content-wrap sunday-2 ">
                    <div class="col-lg-2 col-sm-2 dayselection" id="m_dcontent">
                        <div class="txt">
                            <div class="form-check">
                                <label class="form-check-label weekdy" for="sunday">
                                thursday
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent2">
                        <div class="form-group">
                        <p>Opening Time</p> 
                            <input type="time" class="disable_time form-control dayo" name="thu_open" value="{{isset($user_avail->thu_open) ? $user_avail->thu_open : null }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                        <div class="form-group">
                        <p>Closing Time</p>  
                            <input type="time" class="disable_time form-control dayc" name="thu_close" value="{{isset($user_avail->thu_close) ? $user_avail->thu_close : null }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 fullselection" id="m_dcontent4">
                        <div class="txt">
                            <div class="form-check">
                                @if(isset($user_avail) && $user_avail->thu_open == null && $user_avail->thu_close == null)
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2" checked>
                                @else
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2">
                                @endif
                                <label class="form-check-label " for="sunday-2">
                                Not Available
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row days content-wrap monday-2 ">
                    <div class="col-lg-2 col-sm-2 dayselection" id="m_dcontent">
                        <div class="txt">
                            <div class="form-check">
                                <label class="form-check-label weekdy" for="monday">
                                Friday
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent2">
                        <div class="form-group">
                            <p>Opening Time</p> 
                            <input type="time" class="disable_time form-control dayo" name="fri_open" value="{{isset($user_avail->fri_open) ? $user_avail->fri_open : null }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                        <div class="form-group">
                            <p>Closing Time</p>  
                            <input type="time" class="disable_time form-control dayc" name="fri_close" value="{{isset($user_avail->fri_close) ? $user_avail->fri_close : null }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 fullselection" id="m_dcontent4">
                        <div class="txt">
                            <div class="form-check">
                                @if(isset($user_avail) && $user_avail->fri_open == null && $user_avail->fri_close == null)
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2" checked>
                                @else
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2" >
                                @endif
                                <label class="form-check-label " for="sunday-2">
                                Not Available
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row days content-wrap monday-2 ">
                    <div class="col-lg-2 col-sm-2 dayselection" id="m_dcontent">
                        <div class="txt">
                            <div class="form-check">
                                <label class="form-check-label weekdy" for="monday">
                                Saturday
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent2">
                        <div class="form-group">
                            <p>Opening Time</p> 
                            <input type="time" class="disable_time form-control dayo" name="sat_open" value="{{isset($user_avail->sat_open) ? $user_avail->sat_open : null }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                        <div class="form-group">
                            <p>Closing Time</p>  
                            <input type="time" class="disable_time form-control dayc" name="sat_close" value="{{isset($user_avail->sat_close) ? $user_avail->sat_close : null }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-2 fullselection" id="m_dcontent4">
                        <div class="txt">
                            <div class="form-check">
                                @if(isset($user_avail) && $user_avail->sat_open == null && $user_avail->sat_close == null)
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2" checked>
                                @else
                                <input class="form-check-input dayfull" name="not_available" type="checkbox" id="sunday-2">
                                @endif
                                <label class="form-check-label " for="sunday-2">
                                Not Available
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($user->id))
                <button class="btn btn-success-light mt-3 " type="submit">Update</button>
            @else
                <button class="btn btn-success-light mt-3 " type="submit">Save</button>
            @endif
        </div>
    </form>
    
</div> 
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/date-picker/spectrum.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/date-picker/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multiple-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/multipleselect/multi-select.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/time-picker/toggles.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
          $('#dataTable').DataTable();
    });
</script>
<script>
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).is(':checked')){
                $(this).closest('.content-wrap').find('.disable_time').attr('disabled', 'disabled');
                $(this).closest('.content-wrap').find('.disable_time').val("");
            }
            else{
                $(this).closest('.content-wrap').find('.disable_time').removeAttr('disabled');
            }
        });
    });
</script>
@endsection



