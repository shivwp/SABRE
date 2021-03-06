@extends('layouts.vertical-menu.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet">
<style type="text/css">
    .stc-btn {
    margin: 0px 5px 0px 5px;
}
.sub-btn {
    margin: 0px 5px 0px 5px;
    width: fit-content;
    color: #fff !important;
    background-color: #3c6ec7;
}
</style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->

        <div>

            <h1 class="page-title">{{$title}}</h1>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="#">Job</a></li>

                <li class="breadcrumb-item active" aria-current="page">List</li>

            </ol>

        </div>

    

    <!-- PAGE-HEADER END -->
@endsection
@section('content')

    <!-- ROW-1 OPEN-->

        <!-- ROW-1 OPEN -->

        <div class="row">

        <div class="col-md-12 col-lg-12">

            <div class="card">

            <div class="addnew-ele">

            <a href="{{ route('dashboard.jobs.create') }}" class="btn btn-info-light ">

                {{$buton_name}}

            </a>

        </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <div class="paging-section">
                               <form method="get" class="page-number">
                                <h6 class="page-num">Show</h6>
                                  <select id="pagination" name="paginate"class="form-control select2">

                                    <option value="10" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 10) ? 'selected':''}}>10</option>

                                    <option value="20" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 20) ? 'selected':''}}>20</option>

                                    <option value="30" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 30) ? 'selected':''}}>30</option>

                                    <option value="40" {{ isset($_GET['paginate']) && ($_GET['paginate'] == 40) ? 'selected':''}}>40</option>
                               @if(isset($_GET['page']))<input type="hidden" name="page" value="{{$_GET['page']}}">@endif

                               <input type="submit" name="" style="display:none;">

                           </form>
                          {{--<a href="{{route('dashboard.export-users')}}"  class="form-control src-btn"><i class="fa fa-download" aria-hidden="true"></i></a>--}}

                           <form>

                              <div class="search_bar d-flex">  

                                <select name="status" class="form-control stc-btn">
                                    <option value="">Status</option>
                                    <option value="publish" {{isset($jobs->status) && $jobs->status  == 'publish' ? 'selected' : '' }}> Publish</option>
                                    <option value="draft" {{isset($jobs->status) && $jobs->status   == 'draft' ? 'selected' : '' }}> Draft</option>
                                    <option value="complete" {{isset($jobs->status) && $jobs->status   == 'complete' ? 'selected' : '' }}> Complete</option>
                                    <option value="cancel" {{isset($jobs->status) && $jobs->status   == 'cancel' ? 'selected' : '' }}> Cancel</option>
                                    <option value="available" {{isset($jobs->status) && $jobs->status   == 'available' ? 'selected' : '' }}> Available</option>
                                </select>

                               <input type="" class="form-control" id="search" name="search" value="{{ (request()->get('search') != null) ? request()->get('search') : ''}}" placeholder="Search Title...  "></input>

                              <button type="submit" class="form-control sub-btn" >Submit</button>
                              
                               <a class="form-control src-btn" href="{{ route('dashboard.jobs.index') }}"><i class="angle fe fe-rotate-ccw"></i></a>

                          </div>

                      </form> 

                           </div>

                        <table id="" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th class="wd-15p">Job Title</th>
                                    <th class="wd-15p">Client Name</th>
                                    <th class="wd-15p">Location</th>
                                    <th class="wd-15p">Pay Rate</th>
                                    <th class="wd-15p">Action</th>
                                </tr>
                            </thead>
                        <tbody>

                            @if(!empty($jobs))
                                @foreach($jobs as $item)
                                    <tr>
                                        <td>{{ $item->id ?? '' }}</td>
                                        <td>{{ $item->title ?? '' }}</td>
                                        <td>{{ $item->client_name ?? '' }}</td>
                                        <td>{{ $item->job_location ?? '' }}</td>
                                        <td>${{ $item->pay_rate ?? '' }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.jobs.show', $item->id) }}"><i class="fa fa-eye"></i></a>
                                             @can('permission_edit')
                                             <a class="btn btn-sm btn-secondary" href="{{ route('dashboard.jobs.edit', $item->id) }}"><i class="fa fa-edit"></i> </a>
                                            @endcan
                                            @can('permission_delete')
                                                <form action="{{ route('dashboard.jobs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure');" style="display: inline-block;">
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

                    <div id="pagination">{{{ $jobs->links() }}}</div>

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

 