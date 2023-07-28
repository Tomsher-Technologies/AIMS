@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Dashboard</h1>
                <div class="separator mb-5"></div>
            </div>
            <div class="col-lg-12 col-xl-12">
                <div class="row mb-4">
                    <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                                <img src="{{ asset('assets/images/no_users.svg') }}" alt="">
                                <p class="card-text mb-0 my-2"><b>Total Number of New<br>Students Registered</b> </p>
                                <p class="lead text-center">{{ $total_students }}</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                                <img src="{{ asset('assets/images/no_users.svg') }}" alt="">
                                <p class="card-text mb-0 my-2"><b>Number of <br>Approved Students</b> </p>
                                <p class="lead text-center">{{ $approved_students }}</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                                <img src="{{ asset('assets/images/no_users.svg') }}" alt="">
                                <p class="card-text mb-0 my-2"><b>Number of <br>Rejected Students</b> </p>
                                <p class="lead text-center">{{ $rejected_students }}</p>
                            </div>
                        </a>
                    </div>
                    <!-- <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                                <img src="img/no_certificates.svg" alt="">
                                <p class="card-text mb-0 my-2"><b>Number Of <br> Certificates</b></p>
                                <p class="lead text-center">16</p>
                            </div>
                        </a>
                    </div> -->
                    <!-- <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                                <img src="img/download.png" alt="">
                                <p class="card-text mb-0 my-2"><b>Number of Downloaded <br> Certificates</b></p>
                                <p class="lead text-center">28</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                                <img src="img/visitor.png" alt="">
                                <p class="card-text mb-0 my-2"> <b>Number of Viewed <br> Certificates</b></p>
                                <p class="lead text-center">45</p>
                            </div>
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Certificates</h5>
                        <div class="dashboard-line-chart chart">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
@endsection
@section('header')

@endsection

@section('footer')
<script type="text/javascript">

</script>
@endsection