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
.alert.alert-success.alert-block {
    margin: 20px 20px 0px 20px;
}
.avail{
    font-size: 18px !important;
    margin-left: 20px
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
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>Your Profile has been updated successfully!</strong>
    </div>
    @endif
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
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" placeholder="First Name" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="example@gmail.com" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Profile Image</label>
                        <input type="file" class="form-control" name="pro_image" value="">
                        <input type="hidden" class="form-control" name="pro_image_old" value="{{isset($user) && !empty($user->profile_image) ? $user->profile_image : ''}}">
                    </div>
                </div>
                @if(!empty($user->profile_image))
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        <img src="{{url($user->profile_image)}}">
                    </div>
                </div>
                @endif
               
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
            <h3>On Bording Doc</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Drug & Alcohol Policy</label>
                        <input type="file" class="form-control" name="drug_alcohol">
                        <input type="hidden" class="form-control" name="drug_alcohol_old" value="{{isset($user_meta) && !empty($user_meta['drug_alcohol']) ? $user_meta['drug_alcohol'] : ''}}">
                    </div>
                </div>
               
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                         @if(!empty($user_meta['drug_alcohol']))
                        <img src="{{url($user_meta['drug_alcohol'])}}">
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Employee Demographics</label>
                        <input type="file" class="form-control" name="emp_demo">
                        <input type="hidden" class="form-control" name="emp_demo_old" value="{{isset($user_meta) && !empty($user_meta['emp_demo']) ? $user_meta['emp_demo'] : ''}}"> 
                    </div>
                </div>
               
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                         @if(!empty($user_meta['emp_demo']))
                        <img src="{{url($user_meta['emp_demo'])}}">
                         @endif
                    </div>
                </div>
               
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Rules for Use of Force (RUF)</label>
                        <input type="file" class="form-control" name="rule_force">
                        <input type="hidden" class="form-control" name="rule_force_old" value="{{isset($user_meta) && !empty($user_meta['rule_force']) ? $user_meta['rule_force'] : ''}}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        @if(!empty($user_meta['rule_force']))
                        <img src="{{url($user_meta['rule_force'])}}">
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">SABREE 22 NDA</label>
                        <input type="file" class="form-control" name="sabree_nda">
                        <input type="hidden" class="form-control" name="sabree_nda_old" value="{{isset($user_meta) && !empty($user_meta['sabree_nda']) ? $user_meta['sabree_nda'] : ''}}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        @if(!empty($user_meta['sabree_nda']))
                        <img src="{{url($user_meta['sabree_nda'])}}">
                        @endif
                    </div>
                </div>
                
                {{--<div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Vaccination Card</label>
                        <input type="file" class="form-control" name="vaccination_card">
                        <input type="hidden" class="form-control" name="vaccination_card_old" value="{{isset($user_meta) && !empty($user_meta['vaccination_card']) ? $user_meta['vaccination_card'] : ''}}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group" style="width: 150px;">
                        @if(!empty($user_meta['vaccination_card']))
                        <img src="{{url($user_meta['vaccination_card'])}}">
                        @endif
                    </div>
                </div>--}}
                
            </div>
            <hr>
            <h3>Licenses</h3>
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
                        <img src="{{url($user_meta['bsis_guard_card_certificate'])}}">
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
                        <img src="{{url($user_meta['bsis_exposed_certificate'])}}">
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
                        <img src="{{url($user_meta['ca_ccw_certificate'])}}">
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
                        <img src="{{url($user_meta['hr_certificate'])}}">
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
            <h3>More Info</h3> 
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">What Experience do you have</label>

                        @php
                        $exp = [
                            'military'=>'Military',
                            'police'=>'Police',
                            'close_protection'=>'Close Protection',
                            'executive_protection'=>'Executive Protection',
                            'advance_course'=>'Advanced Driving Course'
                        ];
                        if(isset($experience_data)){
                            $exp_data = explode(',',$experience_data);
                        }
                        @endphp
                        
                        @foreach($exp as $key => $val)
                        <input type="checkbox" name="experience[]" value="{{$key}}" {{isset($exp_data) && in_array($key,$exp_data) ? 'checked' : ''}}>
                        <label for="vehicle1"> {{$val}}</label><br>
                        @endforeach
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Medical Training</label>
                        @php
                        $medical = [
                            'cpr'=>'CPR',
                            'first_aid'=>'First Aid',
                            'trauma'=>'Trauma Protection',
                            'advance_first'=>'Advanced First Aid TCCC',
                            'head_shot'=>'Upload Head Shot'
                        ];
                        if(isset($medical_data)){
                            $med_data = explode(',',$medical_data);
                        }
                        @endphp
                        @foreach($medical as $key => $value)
                        <input type="checkbox" name="medical[]" value="{{$key}}" {{isset($med_data) && in_array($key,$med_data) ? 'checked' : ''}}>
                        <label for="vehicle1">{{$value}}</label><br>
                        @endforeach
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
                        <img src="{{url($user_meta['medical_training_certificate'])}}">
                    </div>
                </div>
                @endif
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Are You Vaccinated ? </label>
                        <select name="vaccinated" class="form-control" required>
                            <option value="">Select</option>
                            <option value="yes" {{isset($user_meta['vaccinated']) && $user_meta['vaccinated'] == "yes" ? 'selected' : ''}}>Yes</option>
                            <option value="no" {{isset($user_meta['vaccinated']) && $user_meta['vaccinated']  == "no" ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                </div>
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
                <div class="txt">
                    <div class="form-check">
                        @if(isset($user_avail->is_available) && ($user_avail->is_available == 0))
                        <input class="form-check-input dayfull" name="is_available" value="0" type="checkbox" id="is_available" style="width: 25px;height: 25px;cursor: pointer;">
                        @else
                        <input class="form-check-input dayfull" name="is_available"  value="1" type="checkbox" id="is_available" style="width: 25px;height: 25px;cursor: pointer;" checked>
                        @endif
                        <label class="form-check-label avail" for="">
                        Is Available
                        </label>
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
                </div>
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
        $('#is_available').click(function(){
            if($(this).is(':checked')){
                $('input[type="time"]').removeAttr('disabled');
                $('#is_available').val(1);
            }
            else{
                $('input[type="time"]').attr('disabled', true);
                $('input[type="time"]').val("");
                $('#is_available').val(0);
            }
        });
    });
</script>
@endsection



