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
                <li class="breadcrumb-item"><a href="{{ route('dashboard.job-apply.index') }}">Job Apply</a></li>
                 @if(isset($job_cate->id))
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
        <form  method="post" action="{{route('dashboard.job-apply.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <input type="hidden" name="id" value="{{ old('id', isset($job_applied->id) ? $job_applied->id : '') }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Job Title</label>
                                <select class="form-control slect-search" name="job_id">
                                    <option>Select</option>
                                    @if(count($jobs) > 0)
                                        @foreach($jobs as $job)
                                            <option value="{{$job->id}}" {{isset($job_applied->job_id) && $job_applied->job_id == $job->id ? 'selected' : ''}}>{{$job->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Guard Name</label>
                                <select class="form-control slect-search" name="user_id">
                                    <option>Select</option>
                                    @if(count($users) > 0)
                                        @foreach($users as $value)
                                            <option value="{{$value->id}}"  {{isset($job_applied->user_id) && $job_applied->user_id == $value->id ? 'selected' : ''}}>{{$value->first_name}} {{$value->last_name}} ({{$value->email }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                       <!--  <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Select</option>
                                    <option value="approve" {{isset($job_applied->status) && $job_applied->status == 'approve' ? 'selected' : ''}}>Approve</option>
                                    <option value="decline" {{isset($job_applied->status) && $job_applied->status == 'decline' ? 'selected' : ''}}>Decline</option>
                                    <option value="pending" {{isset($job_applied->status) && $job_applied->status == 'pending' ? 'selected' : ''}}>Pending</option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                    @if(isset($job_cate->id))
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
          $('#dataTable').DataTable();
          $('.slect-search').select2();

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



