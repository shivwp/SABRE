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
    .fa-square-o{
        text-align: center;
        font-size: 18px;
        color: green;
    }
</style>
@endsection
@section('page-header')
<!-- PAGE-HEADER -->
    <div>
        <h1 class="page-title">{{$title}}</h1>
        
    </div>
<!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">

        <div class="col-md-12 col-lg-12">
            <div class="card">
            <div class="addnew-ele">
            
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
                            <th class="wd-15p">User Name</th>
                            <th class="wd-15p">User Email</th>
                            <th class="wd-15p">Job Title</th>
                            <th class="wd-15p">Arrived On site</th>
                            <th class="wd-15p">Document mileage</th>
                            <th class="wd-15p">Call Local PD</th>
                            <th class="wd-15p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($task))
                        @foreach($task as $item)
                            <tr>
                                <td>{{ $item->id ?? '' }}</td>
                                <td>{{ $item->name ?? '' }}</td>
                                <td>{{ $item->email ?? '' }}</td>
                                <td>{{ $item->title ?? '' }}</td>
                                @if(isset($item->arrive_on_site) && ($item->arrive_on_site == 1))
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-square-o"></i></td>
                                @endif
                                @if(isset($item->document_mileage) && ($item->document_mileage == 1))
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-square-o"></i></td>
                                @endif
                                @if(isset($item->call_local) && ($item->call_local == 1))
                                <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                @else
                                <td class="time_icon"><i class="fa fa-square-o"></i></td>
                                @endif
                                <td>
                                <form  method="post" action="{{ route('dashboard.task-log.store', $item->id) }}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" value="{{$item->id}}">
                                    <input type="hidden" name="job_id" value="{{$item->job_auto_id}}">
                                    <input type="hidden" name="user_id" value="{{$item->user_id}}">
                                    @if($item->task_status == 0)
                                    <button value="done" class="btn btn-sm btn-danger" name="status">Pending</button>
                                    @else
                                    <p value="done" class="btn btn-sm btn-success" name="status">Done</p>
                                    @endif
                                     <!-- <button value="reject" name="status"><i class="fa fa-times"></i></button> -->
                                </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div id="pagination">{{{ $task->links() }}}</div>
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

 