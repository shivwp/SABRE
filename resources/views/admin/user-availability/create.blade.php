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
label.form-check-label.weekdy {
    font-size: 16px;
    font-weight: 600;
    padding-top: 15px;
}
</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">{{$title}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.user-availability.index') }}">User Availability</a></li>
                 @if(isset($user_avail->id))
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
        <form  method="post" action="{{route('dashboard.user-availability.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <input type="hidden" name="id" value="{{ old('id', isset($user_avail->id) ? $user_avail->id : '') }}">
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Category Title" value="{{ old('title', isset($job_cate->title) ? $job_cate->title : '') }}" required>
                            </div>
                        </div>
                    </div> -->
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
                                    <input type="time" class="form-control dayo" name="sun_open" value="08:00">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                                <div class="form-group">
                                <p>Closing Time</p>  
                                    <input type="time" class="form-control dayc" name="sun_close" value="17:00">
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
                                    <input type="time" class="form-control dayo" name="mon_open" value="08:00">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                                <div class="form-group">
                                    <p>Closing Time</p>  
                                    <input type="time" class="form-control dayc" name="mon_close" value="17:00">
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
                                    <input type="time" class="form-control dayo" name="tue_open" value="08:00">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                                <div class="form-group">
                                <p>Closing Time</p>  
                                    <input type="time" class="form-control dayc" name="tue_close" value="17:00">
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
                                    <input type="time" class="form-control dayo" name="wed_open" value="08:00">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                                <div class="form-group">
                                    <p>Closing Time</p>  
                                    <input type="time" class="form-control dayc" name="wed_close" value="17:00">
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
                                    <input type="time" class="form-control dayo" name="thu_open" value="08:00">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                                <div class="form-group">
                                <p>Closing Time</p>  
                                    <input type="time" class="form-control dayc" name="thu_close" value="17:00">
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
                                    <input type="time" class="form-control dayo" name="fri_open" value="08:00">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                                <div class="form-group">
                                    <p>Closing Time</p>  
                                    <input type="time" class="form-control dayc" name="fri_close" value="17:00">
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
                                    <input type="time" class="form-control dayo" name="sat_open" value="08:00">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4" id="m_dcontent3">
                                <div class="form-group">
                                    <p>Closing Time</p>  
                                    <input type="time" class="form-control dayc" name="sat_close" value="17:00">
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($user_avail->id))
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



