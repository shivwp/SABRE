@extends('layouts.vertical-menu.master')
@section('css')
@php
use \App\Http\Controllers\Controller;
@endphp
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet">
<style type="text/css">
    .icon_mid{
        text-align: center;
    }
    .icon_mid i{
        font-size:18px;
        color:green;
    }
    td.time_icon {
    text-align: center;
    font-size: 18px;
    color: #e31515d1;
    }
    .alert.alert-success.alert-block {
    margin: 20px 20px 0px 20px;
}
</style>
@endsection
@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">{{$title}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Job Applied</a></li>
            <li class="breadcrumb-item active" aria-current="page">List</li>
        </ol>
    </div>
<!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">

        <div class="col-md-12 col-lg-12">
            <div class="card">
                @if (session()->has('assign'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>Job Assigned  Successfully</strong>
                </div>
                @endif
            <div class="addnew-ele">
            <a href="{{ route('dashboard.job-apply.create') }}" class="btn btn-info-light ">
                {{$buton_name}}
            </a>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <div class="paging-section">
                    <form method="get" class="page-number">
                        <h6 class="page-num">show</h6>
                          <select id="pagination" name="paginate"class="form-control select2">
                            <option value="10" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 10) ? 'selected':''}}>10</option>
                            <option value="20" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 20) ? 'selected':''}}>20</option>
                            <option value="30" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 30) ? 'selected':''}}>30</option>
                            <option value="50" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 40) ? 'selected':''}}>30</option>
                        </select>
                        @if(isset($_GET['page']))<input type="hidden" name="page" value="{{$_GET['page']}}">@endif
                       <input type="submit" name="" style="display:none;">
                   </form>
                    {{--<a href="{{route('dashboard.export-users')}}"  class="form-control src-btn"><i class="fa fa-download" aria-hidden="true"></i></a>--}}
                   <!--  <form>
                        <div class="search_bar d-flex">  
                            <input type="" class="form-control" id="search" name="search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}" placeholder="Search"></input>
                            <button type="submit" class="form-control src-btn" ><i class="angle fe fe-search"></i></button>
                            <a class="form-control src-btn" href="{{ route('dashboard.users.index') }}"><i class="angle fe fe-rotate-ccw"></i></a>
                        </div>
                    </form>  -->
                </div>
                <table id="" class="table table-striped table-bordered text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th class="wd-15p">Job Title</th>
                            <th class="wd-15p">Guard</th>
                            <th class="wd-15p">Date</th>
                            <th class="wd-15p">BSIS Guard Card</th>
                            <th class="wd-15p">BSIS Firearms License</th>
                            <th class="wd-15p">CA CCW</th>
                            <th class="wd-15p">HR 218</th>
                            <th class="wd-15p">Medical Certificate</th>
                            <!-- <th class="wd-15p">Status</th> -->
                            <th class="wd-15p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($job_applied))
                        @foreach($job_applied as $item)
                            <tr>
                                <td>{{ $item->jobs_auto_id ?? '' }}</td>
                                <td>{{ $item->title ?? '' }}</td>
                                <td>{{ $item->first_name ?? '' }} {{ $item->last_name ?? '' }}</td>
                                <td>{{  \Carbon\Carbon::parse($item->applied_date ?? '')->format('d-M-Y') }}</td>
                                @php
                                $usermeta = Controller::getUserMeta($item->user_id);
                                @endphp

                                @if($usermeta['bsis_guard_card_certificate'] != null)
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-times"></i></td>
                                @endif
                                @if($usermeta['bsis_exposed_certificate'] != null)
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-times"></i></td>
                                @endif
                                @if($usermeta['ca_ccw_certificate'] != null)
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-times"></i></td>
                                @endif
                                @if($usermeta['hr_certificate'] != null)
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-times"></i></td>
                                @endif
                                @if($usermeta['medical_training_certificate'] != null)
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-times"></i></td>
                                @endif
                                <!-- <td class="stts-clas"><a href="{{ route('dashboard.job-apply.edit', $item->jobs_auto_id) }}">{{ $item->job_status ?? '' }}</a></td> -->
                                <td>
                                    {{--<a class="btn btn-sm btn-primary" href=""><i class="fa fa-eye"></i></a>--}}
                                     @can('permission_edit')
                                     <a class="btn btn-sm btn-secondary" href="{{ route('dashboard.job-apply.edit', $item->jobs_auto_id) }}"><i class="fa fa-edit"></i> </a>
                                    @endcan
                                    <form  method="post" action="{{ route('dashboard.job-apply.store', $item->jobs_auto_id) }}" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{$item->jobs_auto_id}}">
                                        <input type="hidden" name="job_id" value="{{$item->job_id}}">
                                        <input type="hidden" name="user_id" value="{{$item->user_id}}">
                                        <input type="hidden" name="applied_date" value="{{$item->applied_date}}">
                                        <button value="assigned" class="btn btn-sm btn-success" name="status"><i class="fa fa-check"></i></button>
                                         <!-- <button value="reject" name="status"><i class="fa fa-times"></i></button> -->
                                    </form>
                                    @can('permission_delete')
                                        <form action="{{ route('dashboard.job-apply.destroy', $item->jobs_auto_id) }}" method="POST" onsubmit="return confirm('Are you sure');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-sm btn-danger" value=""><i class="fa fa-trash"></i></button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div id="pagination">{{{ $job_applied->links() }}}</div>
        </div>
        <!-- TABLE WRAPPER -->
        </div>
            <!-- SECTION WRAPPER -->

        </div>

    </div>

    <!-- ROW-1 CLOSED -->               

@endsection

@section('js')

<script src="{{ URL::asset('assets/plugins/datatable/jquery.dataTables.min.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/datatable/datatable.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>

<script type="text/javascript">

$(document).ready(function() {

  $('#pagination').on('change', function() {

    var $form = $(this).closest('form');

    //$form.submit();

    $form.find('input[type=submit]').click();

    console.log( $form);

  });

});

</script>

@endsection

 