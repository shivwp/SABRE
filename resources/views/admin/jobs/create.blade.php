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
    .heading_detail{
        color: #000;
        font-weight: 700;
    }
    .parc {
    margin-right: 5px;
    margin-bottom: 5px;
    }
    .parc .pip {
    position: relative;
    }
    span.pip img {
    object-fit: cover;
}
.parc .pip .btn {
    position: absolute;
    right: 2px;
    margin-top: 3px;
    background-image: linear-gradient(90deg, #282728 0, #544747);
    height: 20px;
    font-size: smaller;
    min-width: 20px !important;
    line-height: 18px;
    color: #fff;
    padding: 0 !important;
    float: left;
}
</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">{{$title}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.jobs.index') }}">Jobs</a></li>
                 @if(isset($jobs->id))
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
        <form  method="post" action="{{route('dashboard.jobs.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <input type="hidden" name="id" value="{{ old('id', isset($jobs->id) ? $jobs->id : '') }}">
                <h3 class="heading_detail">Job Info</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Job Title</label>
                                <input type="text" class="form-control" name="job_title" placeholder="Job Title" value="{{ old('job_title', isset($jobs->title) ? $jobs->title : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Number of Agents</label>
                                <input type="number" class="form-control" name="agent_number" placeholder="" value="{{ old('agent_number', isset($jobs->number_of_agents) ? $jobs->number_of_agents : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control" name="start_time" placeholder="" value="{{ old('start_time', isset($jobs->start_time) ? $jobs->start_time : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-control" name="end_time" placeholder="" value="{{ old('end_time', isset($jobs->end_time) ? $jobs->end_time : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" value="{{ old('profile_image', isset($jobs->profile_image) ? $jobs->profile_image : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="publish" {{isset($jobs->status) && $jobs->status  == 'publish' ? 'selected' : '' }}> Publish</option>
                                    <option value="draft" {{isset($jobs->status) && $jobs->status   == 'draft' ? 'selected' : '' }}> Draft</option>
                                    <option value="complete" {{isset($jobs->status) && $jobs->status   == 'complete' ? 'selected' : '' }}> Complete</option>
                                    <option value="cancel" {{isset($jobs->status) && $jobs->status   == 'cancel' ? 'selected' : '' }}> Cancel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="">Select</option>
                                    @if(count($job_cate) > 0)
                                        @foreach($job_cate as $cate)
                                            <option value="{{$cate->title}}" {{isset($jobs->category) && $jobs->category  == $cate->title ? 'selected' : '' }}> {{$cate->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3 class="heading_detail">Basic Info</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Client Name</label>
                                <input type="text" class="form-control" name="client_name" placeholder="Client Name" value="{{ old('client_name', isset($jobs->client_name) ? $jobs->client_name : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Start Date of Assignment</label>
                                <input type="date" class="form-control" name="start_assign_date" placeholder="Date of Assignment" value="{{ old('start_assign_date', isset($jobs->assignment_start_date) ? $jobs->assignment_start_date : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">End Date of Assignment</label>
                                <input type="date" class="form-control" name="end_assign_date" placeholder="Date of Assignment" value="{{ old('end_assign_date', isset($jobs->assignment_end_date) ? $jobs->assignment_end_date : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Point of Contact</label>
                                <input type="text" class="form-control" name="point_contact" placeholder="Point of Contact" value="{{ old('point_contact', isset($jobs->point_contactname) ? $jobs->point_contactname : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Point of Contact Cell Phone</label>
                                <input type="number" class="form-control" name="point_contact_phone" placeholder="Point of Contact Cell Phone" value="{{ old('point_contact_phone', isset($jobs->point_phonenumber) ? $jobs->point_phonenumber : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" placeholder="Location" value="{{ old('Location', isset($jobs->job_location) ? $jobs->job_location : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Address" value="{{ old('point_contact', isset($jobs->job_address) ? $jobs->job_address : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Pay rate</label>
                                <input type="number" class="form-control" name="pay_rate" placeholder="" value="{{ old('pay_rate', isset($jobs->pay_rate) ? $jobs->pay_rate : '') }}" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3 class="heading_detail">Detail Specifies</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Agent Attire</label>
                                <input type="text" class="form-control" name="agent_attire" placeholder="Agent Attire" value="{{ old('agent_attire', isset($jobs->agent_attire) ? $jobs->agent_attire : '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Armed</label>
                                <select name="armed" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="yes" {{isset($jobs->armed) && $jobs->armed == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{isset($jobs->armed) && $jobs->armed == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Concealed</label>
                                <select name="conceal" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="yes" {{isset($jobs->concealed) && $jobs->concealed  == 'yes' ? 'selected' : '' }}> Yes</option>
                                    <option value="no" {{isset($jobs->concealed) && $jobs->concealed   == 'no' ? 'selected' : '' }}> No</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    @if(isset($jobs->id))
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
<script>
    $(document).ready(function() {
          $('#dataTable').DataTable();

    });
    function removeImage(data) {
        console.log(data);
        var inputvalue = $('#gallery_img').val();
        var ary = JSON.parse(inputvalue);
        console.log(ary);

        ary.splice($.inArray(data, ary), 1);
        var asd = JSON.stringify(ary);
        $('.pip[data-title="' + data + '"]').remove();
        $('#gallery_img').val(asd);
    }
</script>
@endsection



