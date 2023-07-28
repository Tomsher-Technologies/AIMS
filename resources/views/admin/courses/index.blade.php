@extends('admin.layouts.app')
@section('content')
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-0">
                <h1>All Courses</h1>
                <div class="text-zero top-right-button-container">
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row list disable-text-selection" data-check-all="checkAll">
        <div class="col-lg-12 col-md-12 mb-4">
            <div class="card recent_certificate">
                <div class="card-body">
                    <div class="data_card">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Sl. No</th>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">Course Description</th>
                                        <th scope="col">Banner Image</th>
                                        <th scope="col">Lot 1</th>
                                        <th scope="col">Lot 2</th>
                                        <th scope="col">Uploaded Date</th>
                                        <th scope="col">Uploaded User</th>
                                        <th scope="col">No of Downloads</th>
                                        <th scope="col">No of Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="certificate_view.html">170322</a></td>
                                        <td>AE220326P</td>
                                        <td>76184040G0</td>
                                        <td>76184040G1</td>
                                        <td>SBV4410007</td>
                                        <td>SBV4410005</td>
                                        <td>30/06/2022</td>
                                        <td>Jacob</td>
                                        <td>25</td>
                                        <td>38</td>
                                    </tr>
                                    <tr>
                                        <td><a href="certificate_view.html">170450</a></td>
                                        <td>AE220326P</td>
                                        <td>76184040G0</td>
                                        <td>76184040G2</td>
                                        <td>SBV4410007</td>
                                        <td>SBV4410006</td>
                                        <td>28/06/2022</td>
                                        <td>Mark</td>
                                        <td>55</td>
                                        <td>29</td>
                                    </tr>
                                    <tr>
                                        <td><a href="certificate_view.html">170322</a></td>
                                        <td>AE220326P</td>
                                        <td>76184040G0</td>
                                        <td>76184040G5</td>
                                        <td>SBV4410007</td>
                                        <td>SBV4410007</td>
                                        <td>30/06/2022</td>
                                        <td>Jacob</td>
                                        <td>25</td>
                                        <td>24</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('header')
@endsection
@section('footer')
<script type="text/javascript">
</script>
@endsection