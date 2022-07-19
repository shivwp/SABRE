@extends('layouts.vertical-menu.master')
@section('css')
@php
use \App\Http\Controllers\Controller;
@endphp
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet">
<style type="text/css">
    span.cheklst {
    text-transform: capitalize;
    background-color: #325187;
    color: #fff;
    padding: 5px;
}
.fa-square-o{
        text-align: center;
        font-size: 18px;
        color: green;
    }
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
                               {{--<form method="get" class="page-number">
                                <h6 class="page-num">show</h6>
                                  <select id="pagination" name="paginate"class="form-control select2">

                                    <option value="10" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 10) ? 'selected':''}}>10</option>

                                    <option value="20" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 20) ? 'selected':''}}>20</option>

                                    <option value="30" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 30) ? 'selected':''}}>30</option>

                                    <option value="50" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 40) ? 'selected':''}}>30</option>
                               @if(isset($_GET['page']))<input type="hidden" name="page" value="{{$_GET['page']}}">@endif

                               <input type="submit" name="" style="display:none;">

                           </form>
                          <a href="{{route('dashboard.export-users')}}"  class="form-control src-btn"><i class="fa fa-download" aria-hidden="true"></i></a>--}}

                          <!--  <form>

                              <div class="search_bar d-flex">  

                               <input type="" class="form-control" id="search" name="search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}" placeholder="Search"></input>

                              <button type="submit" class="form-control src-btn" ><i class="angle fe fe-search"></i></button>

                               <a class="form-control src-btn" href="{{ route('dashboard.users.index') }}"><i class="angle fe fe-rotate-ccw"></i></a>

                          </div>

                      </form> 
 -->
                           </div>

                        <table id="" class="table table-striped table-bordered text-nowrap w-100">
                            <tr>
                                <th class="wd-15p">Job Title</th>
                                <td>{{$jobs->title}}</td>
                            </tr>
                            <tr>
                                <th class="wd-15p">Job Category</th>
                                <td>{{$jobs->category}}</td>
                            </tr>
                            <tr>
                                <th class="wd-15p">Pay rate</th>
                                <td>${{$jobs->pay_rate}}</td>
                            </tr>
                            <tr>
                                <th class="wd-15p">Start Date</th>
                                <td>{{$jobs->assignment_start_date}}</td>
                            </tr>
                            <tr>
                                <th class="wd-15p">End Date</th>
                                <td>{{$jobs->assignment_end_date}}</td>
                            </tr>
                            <tr>
                                <th class="wd-15p">Job Location</th>
                                <td>{{$jobs->job_location}}</td>
                            </tr>
                            <tr>
                                <th class="wd-15p">Assigned Guards</th>
                                <td>
                                @if(!empty($asigne_job))
                                    @foreach($asigne_job as $item)
                                        @php
                                            $usermeta = Controller::getUserMeta($item->user_id);
                                        @endphp
                                         {{ $item->name ?? '' }} ({{ $item->email ?? '' }}) <br>
                                    @endforeach
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="wd-15p">Job Expense</th>
                                    <td>
                                    <table>
                                        @if(!empty($job_meta))
                                            @foreach($job_meta as $key => $val)
                                            <tr>
                                                <th class="wd-15p">{{$key}}</th>
                                                <!-- <td><img src="{{url('certificate/'.$val)}}" style="width: 100px;margin-left: 20px;" ></td> -->
                                                <td class="wd-15p"><a href="{{url('certificate/'.$val)}}" title="Download" style="color: #325187;" download><i class="fa fa-download" aria-hidden="true"></i></a></td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </td>
                            </tr>

                        </table>
                        <hr>
                        <h3> Task Status</h3>
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
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($task))
                                @foreach($task as $val)
                                    <tr>
                                        <td>{{ $val->id ?? '' }}</td>
                                        <td>{{ $val->name ?? '' }} </td>
                                        <td>{{ $val->email ?? '' }}</td>
                                        <td>{{ $val->title ?? '' }}</td>
                                        @if(isset($val->arrive_on_site) && ($val->arrive_on_site == 1))
                                        <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                        @else
                                        <td class="time_icon"><i class="fa fa-square-o"></i></td>
                                        @endif
                                        @if(isset($val->document_mileage) && ($val->document_mileage == 1))
                                        <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                        @else
                                        <td class="time_icon"><i class="fa fa-square-o"></i></td>
                                        @endif
                                        @if(isset($val->call_local) && ($val->call_local == 1))
                                        <td class="icon_mid"><i class="fa fa-check-square-o"></i></td>
                                        @else
                                        <td class="time_icon"><i class="fa fa-square-o"></i></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div id="pagination">{{{ $asigne_job->links() }}}</div>

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

 