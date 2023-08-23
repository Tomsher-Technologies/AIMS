@extends('admin.layouts.app')
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
                  <h5 class="mb-4">Personal Details</h5>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">First Name</label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>Muhsil   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row     align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">Last Name</label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>Muhsil   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">Email</label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>Muhsil@email.com   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">Phone Number</label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>+971 123456789   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">Gender</label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>Male  </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">Date Of Birth</label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>10-5-1550   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">Address </label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>10-5-1550   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">UAE </label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>10-5-1550   </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">State </label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>Dubai  </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group row align-items-center" bis_skin_checked="1">
                           <label for=""
                              class="col-sm-2 col-form-label">City </label>
                           <div class="col-sm-10" bis_skin_checked="1">
                              <p>Dubai  </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                     </div>
                  </div>
                  11. Student Image
                  12. Passport Image
                  13. Enrollment Form (pdf download)
                  14. Fee Pending
                  15. Due Date
                  </form>
               </div>
            </div>
         </div>
         <div class="row">
            
            <div class="col-md-12">
              
      
            <div class="card mb-4 mt-4" bis_skin_checked="1">
              
                     <div class="card-body" bis_skin_checked="1">
                        <h5 class="card-title">Course Details</h5>
                        <table class="table table-hover">
                           <thead>
                              <tr>
                                 <th scope="col">#</th>
                                 <th scope="col">Course Name</th>
                                 <th scope="col">Course Package  </th>
                                 <th scope="col">Start Date</th>
                                 <th scope="col">End Date</th>
                                 <th scope="col">Fee Pending</th>
                                 <th scope="col">Due Date</th>
                                 <th scope="col">Actions</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <th scope="row">1</th>
                                 <td>Course</td>
                                 <td>TEST</td>
                                 <td>13-10-2023</td>
                                 <td>13-10-2024</td>
                                 <td>AED 5464</td>
                                 <td>13-10-2024</td>
                                 <td>
                                    <a class="btn btn-primary mb-1" data-toggle="collapse" href="#collapseExample01" role="button" aria-expanded="true" aria-controls="collapseExample01">Attended Classes</a>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="p-0" colspan="10">
                                    <div class="collapse" id="collapseExample01" bis_skin_checked="1" style="">
                                       <div class="p-2 border" bis_skin_checked="1">
                                          
                                       <h5 class="card-title">Attended Classes Details</h5>
                                       <table class="table table-striped">
   <thead>
      <tr>
         <th scope="col">#</th>
         <th scope="col">Title</th>
         <th scope="col">Attended Status</th>
         <th scope="col">Attended date</th>
       
      </tr>
   </thead>
   <tbody>
      <tr>
         <th scope="row">01</th>
         <th scope="row">TEST TITLE</th>
         <td> <span class="badge badge-pill badge-outline-success mb-1">Completed</span></td>
         <td>12-5-2024</td>
        
      </tr>
      <tr>
      <th scope="row">01</th>

      <th scope="row">TEST TITLE 1</th>
         <td><span class="badge badge-pill badge-outline-warning mb-1">Warning</span></td>
         <td>12-5-2024</td>
      </tr>
      <tr>
      <th scope="row">01</th>

      <th scope="row">TEST TITLE 2</th>
         <td><span class="badge badge-pill badge-outline-info mb-1">Info</span></td>
         <td>12-5-2024</td>
      </tr>
   </tbody>
</table>
                                       

                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <th scope="row">1</th>
                                 <td>Course</td>
                                 <td>TEST</td>
                                 <td>13-10-2023</td>
                                 <td>13-10-2023</td>
                                 <td>AED 79323</td>
                                 <td>13-10-2024</td>
                                 <td>
                                    <a class="btn btn-primary mb-1" data-toggle="collapse" href="#collapseExample02" role="button" aria-expanded="true" aria-controls="collapseExample02">Attended Classes</a>
                                    
                                 </td>
                              </tr>
                              <tr>
                              <td class="p-0" colspan="10">
                                    <div class="collapse" id="collapseExample02" bis_skin_checked="1" style="">
                                       <div class="p-2 border" bis_skin_checked="1">



                                    </div>
                                    </div>
                                 </td>
                              </tr>
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
               <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Detail Picture" class="card-img-top">
               <!-- <p class="mb-1 text-center"><a href="#"><span class="badge badge-pill badge-outline-theme-2 mb-1 ">DOWNLOAD</span> </a> -->
               </p>
            </div>
         </div>
         <div class="card mb-5" bis_skin_checked="1">
            <div class="card-body" bis_skin_checked="1">
               <h5 class="card-title"><span>Enrollment Form</span></h5>
               <a class="btn btn-primary" target="_blank" href="Pages.Misc.Invoice.Standalone.html">Download</a>
            </div>
         </div>

       
               <div class="card mt-4 mb-4 d-none d-lg-block" bis_skin_checked="1">
                  <div class="card-body" bis_skin_checked="1">
                     <h5 class="card-title"><span>Passport Image</span></h5>
                     <div class=" social-image-row gallery" bis_skin_checked="1">
                        <div class="" bis_skin_checked="1">
                           <a href="https://images.unsplash.com/photo-1581553672347-95d9444c0d2c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80">
                           <img class="img-fluid border-radius" src="https://images.unsplash.com/photo-1581553672347-95d9444c0d2c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80"></a>
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