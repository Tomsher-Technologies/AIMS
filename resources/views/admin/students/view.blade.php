@extends('admin.layouts.app')
@section('title', 'Student Details')
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-12">
         <div class="mb-3 d-flex align-items-center justify-content-between">
            <h1 class="m-0 p-0">Student Details</h1>
            <div class="btn_group">
               <a href="{{ route('students') }}" class="btn btn-primary btn-lg top-right-button btn_primary">Back</a>
            </div>
         </div>
         <div class="separator mb-5"></div>
      </div>
   </div>
   <div class="row list " data-check-all="checkAll">
      <div class="col-lg-9 col-md-9 mb-4">
         <div class="card recent_certificate">
            <div class="card-body student-details">
               <div class="data_card">
                  <h5 class="mb-4">Personal Details 
                     @if($user->is_approved == 1)
                        <span class="badge badge-pill badge-outline-success mb-1 ml-3">Approved</span>
                        @if($user->is_active == 1)
                           <span class="badge badge-pill badge-success mb-1">Active</span>
                        @else
                           <span class="badge badge-pill badge-danger mb-1">Inactive</span>
                        @endif
                     @else
                        <span class="badge badge-pill badge-outline-danger mb-1 ml-3">Rejected</span>
                     @endif

                  </h5>
                  
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">First Name</label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->first_name ?? '' }}   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row     align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Last Name</label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->last_name ?? ''}}   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Email</label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->email ?? ''}}  </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Phone Number</label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->phone_code ?? '' }} {{ $user->user_details->phone_number ?? '' }}   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Gender</label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ ucfirst($user->user_details->gender) ?? '' }}   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Date Of Birth</label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->date_of_birth ?? '' }}    </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Address </label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->address ?? '' }}   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Country </label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->country_name->name ?? '' }}   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">State </label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->state_name->name ?? '' }}     </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">City </label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ $user->user_details->city ?? '' }}     </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-3 col-form-label">Register Date </label>
                           <div class="col-sm-9" bis_skin_checked="1">
                              <p>{{ date('Y-m-d', strtotime($user->created_at)) }}     </p>
                           </div>
                        </div>
                     </div>
                  </div>
                  
                  </form>
               </div>
            </div>
         </div>

         <div class="row">
            
            <div class="col-md-12">
              
      
               <div class="card mb-4 mt-4" bis_skin_checked="1">
               
                  <div class="card-body" bis_skin_checked="1">
                     <h5 class="card-title">Course Details</h5>
                     <table class="table">
                        <thead>
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">Course Name</th>
                              <th scope="col">Course Package  </th>
                              <th scope="col" class="text-center">Start Date</th>
                              <th scope="col" class="text-center">End Date</th>
                              <th scope="col" class="text-center">Fee Pending</th>
                              <th scope="col" class="text-center">Due Date</th>
                              <th scope="col" class="text-center">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php  $j = 1 ; @endphp
                           @if(!empty($user->student_all_packages[0]))
                              @foreach($user->student_all_packages as $pack)
                              <tr>
                                 <th scope="row">{{$j}}</th>
                                 <td> {{ $pack->course->name ?? '' }} </td>
                                 <td>{{ $pack->package->package_title ?? '' }} </td>
                                 <td class="text-center">{{ $pack->start_date ?? '' }} </td>
                                 <td class="text-center">{{ $pack->end_date ?? '' }}</td>
                                 <td class="text-center">{{ ($pack->fee_pending == 0) ? 'No' : 'Yes' }}</td>
                                 <td class="text-center">{{ ($pack->due_date != null) ? $pack->due_date : '-' }}</td>
                                 <td class="text-center">
                                    <a class="btn btn-primary mb-1" data-toggle="collapse" href="#view_details_{{$pack->id}}" role="button" aria-expanded="true" aria-controls="view_details_{{$pack->id}}">Classes</a>
                                 </td>
                              </tr>

                              <tr>
                                 <td class="p-0" colspan="10">
                                    <div class="collapse" id="view_details_{{$pack->id}}" bis_skin_checked="1" style="">
                                       <div class="p-2 border" bis_skin_checked="1">
                                          
                                          <h5 class="card-title class_details">Class Details</h5>
                                          <table class="table table-striped">
                                             <thead>
                                                <tr>
                                                   <th scope="col">#</th>
                                                   <th scope="col">Title</th>
                                                   <th scope="col" class="text-center">Mandatory Status</th>
                                                   <th scope="col" class="text-center">Attended Status</th>
                                                   <th scope="col" class="text-center">Attended date</th>
                                                
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @php  $i = 1 ; @endphp
                                                @foreach($pack->classes as $class)
                                                   <tr>
                                                      <th scope="row">{{ $i }}</th>
                                                      <th scope="row">{{ $class->class_details->class_name ?? '' }}</th>
                                                      <td class="text-center"> 
                                                         @if($class->class_details->is_mandatory == 1 )
                                                            <span class="badge badge-pill badge-outline-success mb-1">Yes</span>
                                                         @else
                                                            <span class="badge badge-pill badge-outline-danger mb-1">No</span>
                                                         @endif
                                                      </td>
                                                      <td class="text-center"> 
                                                         @if($class->is_attended == 1 )
                                                            <span class="badge badge-pill badge-success mb-1">Yes</span>
                                                         @else
                                                            <span class="badge badge-pill badge-danger mb-1">No</span>
                                                         @endif
                                                      </td>
                                                      <td class="text-center"> {{ $class->attended_date ?? '-' }}</td>
                                                   
                                                   </tr>
                                                   @php  $i++ ; @endphp
                                                @endforeach
                                             </tbody>
                                          </table>
                                       

                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              @php  $j++; @endphp
                              @endforeach
                           @else
                                 <tr>
                                    <td colspan="8" class="text-center">No data found.</td>
                                 </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
                  
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-12">
               <div class="card mb-4 mt-1" bis_skin_checked="1">
                  <div class="card-body" bis_skin_checked="1">
                     <h5 class="card-title">Mock Test Results</h5>
                        <table class="table table-bordered mb-0">
                           <thead>
                              <tr>
                                    <th scope="col" colspan="2"></th>
                                    <th scope="col" colspan="4" class="text-center">Listening</th>
                                    <th scope="col" colspan="4" class="text-center">Reading</th>
                              </tr>
                              <tr>
                                    <th scope="col" class="text-center">Sl. No</th>
                                    <th scope="col" class="text-center">Test Date</th>
                                    <th scope="col" class="text-center">A</th>
                                    <th scope="col" class="text-center">B</th>
                                    <th scope="col" class="text-center">C</th>
                                    <th scope="col" class="text-center">Total</th>
                                    <th scope="col" class="text-center">A</th>
                                    <th scope="col" class="text-center">B</th>
                                    <th scope="col" class="text-center">C</th>
                                    <th scope="col" class="text-center">Total</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if(!empty($user->mock_tests[0]))
                                 @php  $k = 1 ; @endphp
                                 @foreach($user->mock_tests as $mock)
                                    <tr>
                                       <td class="text-center">{{ $k }}</td>
                                       <td class="text-center">{{ $mock->test_date }}</td>
                                       <td class="text-center">{{ $mock->listening_a ?? '-'}}</td>
                                       <td class="text-center">{{ $mock->listening_b ?? '-' }}</td>
                                       <td class="text-center">{{ $mock->listening_c ?? '-' }}</td>
                                       <td class="text-center"><b>{{ $mock->listening_total ?? '-' }}</b></td>
                                       <td class="text-center">{{ $mock->reading_a ?? '-' }}</td>
                                       <td class="text-center">{{ $mock->reading_b ?? '-' }}</td>
                                       <td class="text-center">{{ $mock->reading_c ?? '-' }}</td>
                                       <td class="text-center"><b>{{ $mock->reading_total ?? '-' }}</b></td>
                                    </tr>
                                    @php  $k++; @endphp
                                 @endforeach
                              @else
                                    <tr>
                                       <td colspan="13" class="text-center">No data found.</td>
                                    </tr>
                              @endif

                           </tbody>
                        </table>
                  </div>
               </div>
            </div>
         </div>

      </div>


      <div class="col-md-3">
         <div class="card mb-4" bis_skin_checked="1">
            <!-- <div class="position-absolute card-top-buttons" bis_skin_checked="1"><button class="btn btn-outline-white icon-button"><i class="simple-icon-pencil"></i></button></div> -->
            <div class="card-body" bis_skin_checked="1">
               <h5 class="card-title"><span>Student Image</span></h5>
               @if($user->user_details->profile_image != null)
                  <img src="{{ asset($user->user_details->profile_image) }}" alt="Detail Picture" class="card-img-top">
               @endif
               <!-- <p class="mb-1 text-center"><a href="#"><span class="badge badge-pill badge-outline-theme-2 mb-1 ">DOWNLOAD</span> </a> -->
               </p>
            </div>
         </div>
         <div class="card mb-5" bis_skin_checked="1">
            <div class="card-body" bis_skin_checked="1">
               <h5 class="card-title"><span>Enrollment Form</span></h5>
               @if($user->user_details->enrollment_form != null)
                  <a class="btn btn-primary" target="_blank" href="{{ asset($user->user_details->enrollment_form) }}">Download</a>
               @endif
            </div>
         </div>

       
         <div class="card mt-4 mb-4 d-none d-lg-block" bis_skin_checked="1">
            <div class="card-body" bis_skin_checked="1">
               <h5 class="card-title"><span>Passport Image</span></h5>
               <div class=" social-image-row gallery" bis_skin_checked="1">
                  <div class="" bis_skin_checked="1">
                     @if($user->user_details->passport != null)
                        <a href="{{ asset($user->user_details->passport) }}" target="_blank">
                        <img class="img-fluid border-radius" src="{{ asset($user->user_details->passport) }}"></a>
                     @endif
                  </div>
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
<script type="text/javascript"></script>
@endsection