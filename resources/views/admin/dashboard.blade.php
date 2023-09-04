@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Dashboard</h1>
                <div class="separator mb-5"></div>
                <div class="form-group col-md-3 pl-0">
                    <label for="#">Filter By Date</label>
                    <input type="text" class="form-control" value="YYYY-MM-DD" id="date_filter" name="date_filter" placeholder="YYYY-MM-DD">
                </div>
            </div>
            <div class="col-lg-12 col-xl-12">
                <div class="row mb-4">
                    <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                            <div class="glyph-icon simple-icon-people" bis_skin_checked="1" style="font-size: 50px;"></div>
                                <!-- <img src="{{ asset('assets/images/no_users.svg') }}" alt=""> -->
                                <p class="card-text mb-0 my-2"><b>Total Number of New<br>Students Registered</b> </p>
                                <p class="lead text-center" id="total_students"></p>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                            <div class="glyph-icon simple-icon-people" bis_skin_checked="1" style="font-size: 50px;"></div>
                                <!-- <img src="{{ asset('assets/images/no_users.svg') }}" alt=""> -->
                                <p class="card-text mb-0 my-2"><b>Number of <br>Approved Students</b> </p>
                                <p class="lead text-center" id="approved_students"></p>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 mb-4 mb-xl-0">
                        <a href="#" class="card">
                            <div class="card-body text-center align-items-center">
                            <div class="glyph-icon simple-icon-people" bis_skin_checked="1" style="font-size: 50px;"></div>
                                <!-- <img src="{{ asset('assets/images/no_users.svg') }}" alt=""> -->
                                <p class="card-text mb-0 my-2"><b>Number of <br>Rejected Students</b> </p>
                                <p class="lead text-center" id="rejected_students"></p>
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
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>

<script type="text/javascript">
    var today = getToday();

    function getToday() {
        var d = new Date();

        var month = d.getMonth() + 1;
        var day = d.getDate();

        var output = d.getFullYear() + '-' +
            (('' + month).length < 2 ? '0' : '') + month + '-' +
            (('' + day).length < 2 ? '0' : '') + day;

        return output;
    }

    $('#date_filter').daterangepicker({
		locale: {
            format: 'MMM D, Y'
        },
		ranges: {
            'Past 24 Hours': [moment().subtract(1, 'days'), moment()],
            'Today': [moment().startOf('day'), moment().endOf('day')],
            'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                'month')],
        },
       
    });

	$('#date_filter').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
		var hotel = $('#hotel').val();
		
        getCounts(startDate, endDate, hotel);
    });

    getCounts(today, today, '');

	function getCounts(startDate, endDate, hotel) {
        $.ajax({
            url: "{{ route('dashboard-counts')}}",
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}",
                "start": startDate,
                "end": endDate
            },
            success: function(response) {
                var resp = JSON.parse(response);

                $('#total_students').html(resp.data.total_students);
				$('#approved_students').html(resp.data.approved_students);
                $('#rejected_students').html(resp.data.rejected_students);
               
            }
        });
    }

</script>
@endsection