@extends('layouts.vertical-menu.master')
@section('css')
<style>
	.bg-secondary {
    background-color: #d43f8d!important;
}
.w-20 {
    width: 33.3%!important;
}
.w-30 {
    width: 33.3%!important;
}
.w-25 {
    width: 33.3%!important;
}
</style>
@endsection
@section('page-header')
                        <!-- PAGE-HEADER -->
                            <div>
                                <h1 class="page-title">Dashboard</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </div>
                        <!-- PAGE-HEADER END -->
@endsection
@section('content')	
						<!-- ROW-1 -->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xl-6">
								<div class="row">
									<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6">
										<div class="card">
											<div class="card-body text-center statistics-info">
												<div class="counter-icon bg-primary mb-0 box-primary-shadow">
													<i class="fa fa-briefcase text-white"></i>
												</div>
												<h6 class="mt-4 mb-1">Total Jobs/Tasks</h6>
												<h2 class="mb-2 number-font">{{$total_job}}</h2>
												<p class="text-muted"></p>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6">
										<div class="card">
											<div class="card-body text-center statistics-info">
												<div class="counter-icon bg-secondary mb-0 box-secondary-shadow" >
													<i class="fa fa-users text-white"></i>
												</div>
												<h6 class="mt-4 mb-1">Total Guards</h6>
												<h2 class="mb-2 number-font">{{$guard}}</h2>
												<p class="text-muted"></p>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6">
										<div class="card">
											<div class="card-body text-center statistics-info">
												<div class="counter-icon bg-success mb-0 box-success-shadow">
													<i class="fa fa-tasks text-white"></i>
												</div>
												<h6 class="mt-4 mb-1">Complete Jobs/Tasks</h6>
												<h2 class="mb-2  number-font">{{$complete_job}}</h2>
												<p class="text-muted"></p>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6">
										<div class="card">
											<div class="card-body text-center statistics-info">
												<div class="counter-icon bg-info mb-0 box-info-shadow">
													<i class="fe fe-user text-white"></i>
												</div>
												<h6 class="mt-4 mb-1">Assigned Guards</h6>
												<h2 class="mb-2  number-font">{{$assign_guard}}</h2>
												<p class="text-muted"></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Total</h3>
									</div>
									<div class="card-body">
										<div id="echart1" class="chart-donut chart-dropshadow"></div>
										<div class="mt-4">
											<span class="ml-5"><span class="dot-label bg-info mr-2"></span>Jobs</span>
											<span class="ml-5"><span class="dot-label bg-success mr-2"></span>Guards</span>
										</div>
									</div>
								</div>
							</div><!-- COL END -->
						</div>
						<!-- ROW-1 END -->

						<!--  ROW-3 -->
						<div class="row">
						
							<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Security Check List </h3>
									</div>
									<div class="card-body">
										<small class="text-muted">Check List</small>
										<h2 class="number-font">{{$checklist}}</h2>
										<div class="progress grouped h-3">
											<div class="progress-bar w-25 bg-primary " role="progressbar"></div>
											<div class="progress-bar w-30 bg-danger" role="progressbar"></div>
											<div class="progress-bar w-20 bg-warning" role="progressbar"></div>
										</div>
										<div class="row mt-3 pt-3">
											<div class="col border-right">
												<p class=" number-font1 mb-0"><span class="dot-label bg-primary"></span>Arrived On Site</p>
												<h5 class="mt-2 font-weight-semibold mb-0">{{$checklist_arrived}}</h5>
											</div>
											<div class="col  border-right">
												<p class=" number-font mb-0"><span class="dot-label bg-danger"></span>Document Mileage</p>
												<h5 class="mt-2 font-weight-semibold mb-0">{{$checklist_document}}</h5>
											</div>
											<div class="col">
												<p class="number-font1 mb-0"><span class="dot-label bg-warning"></span>Call Local PD</p>
												<h5 class="mt-2 font-weight-semibold mb-0">{{$checklist_call}}</h5>
											</div>
										</div>
									</div>
								</div>
				
							</div><!-- COL END -->
						</div>
						<!-- ROW-3 END -->

				

						
					</div>
				</div>
				<!-- CONTAINER END -->
            </div>
@endsection
@section('js')
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/echarts/echarts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/apexcharts/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>
<script src="{{ URL::asset('assets/js/index1.js') }}"></script>
@endsection




