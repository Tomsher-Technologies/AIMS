    <div class="menu">
        
    
    <div class="main-menu">
            <div class="scroll">
                <ul class="list-unstyled">
                   
                    <li class="{{ areActiveRoutes(['admin.dashboard']) }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="simple-icon-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @if(in_array('manage_course', Auth::user()->permissions) || in_array('manage_divisions', Auth::user()->permissions) || in_array('manage_packages', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['all-courses', 'course.create','course.edit','all-divisions', 'division.create','division.edit','course-packages', 'packages.create','packages.edit']) }}">
                            <a href="#courses">
                                <i class="simple-icon-organization"></i> Courses
                            </a>
                        </li>
                    @endif

                    @if(in_array('manage_classes', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['classes', 'class.create','class.edit']) }}">
                            <a href="{{ route('classes') }}">
                                <i class="simple-icon-notebook"></i> Classes
                            </a>
                        </li>
                    @endif

                    @if(in_array('manage_teachers', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['teachers', 'teacher.create','teacher.edit']) }}">
                            <a href="{{ route('teachers') }}">
                                <i class="simple-icon-user"></i> Teachers
                            </a>
                        </li>
                    @endif

                    @if(in_array('assign_teacher', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['assign-teachers', 'assign-teacher.create','assign-teacher.edit']) }}">
                            <a href="{{ route('assign-teachers') }}">
                                <i class="simple-icon-user-following"></i>Assign Teachers
                            </a>
                        </li>
                    @endif

                    @if(in_array('manage_bookings', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'staff')
                        <li class="{{ areActiveRoutes(['student.bookings']) }}">
                            <a href="{{ route('student.bookings') }}">
                                <i class="simple-icon-clock"></i> Student Bookings
                            </a>
                        </li>
                    @endif

                    @if(in_array('attendance', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'staff')
                        <li class="{{ areActiveRoutes(['attendance-list','attendance','edit-attendance','view-attendance']) }}">
                            <a href="#attendances">
                                <i class="simple-icon-calendar"></i>
                                Student Attendance
                            </a>
                        </li>
                    @endif

                    @if(in_array('mock_test', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['mock-tests','mock.create','mock.edit','mock.bulk-create']) }}">
                            <a href="{{ route('mock-tests') }}">
                                <i class="simple-icon-trophy"></i>Mock Test Results
                            </a>
                        </li>
                    @endif

                    @if(in_array('manage_students', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['students','view-student','student.create','student.edit','student.bulk-create']) }}">
                            <a href="{{ route('students') }}">
                                <i class="simple-icon-people"></i> Students
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['admins','admin.create','admin.edit']) }}">
                            <a href="{{ route('admins') }}">
                                <i class="simple-icon-people"></i> Admins
                            </a>
                        </li>
                    @endif
                    @if(in_array('remarks', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                        <li class="{{ areActiveRoutes(['remarks']) }}">
                            <a href="{{ route('remarks') }}">
                                <i class="simple-icon-bubbles"></i> Student Remarks
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        
       
        <div class="sub-menu">
                <div class="scroll">

                    <ul class="list-unstyled" data-link="attendances">
                        <li class="{{ areActiveRoutes(['attendance-list','edit-attendance','view-attendance']) }}">
                            <a href="{{ route('attendance-list') }}">
                                <i class="simple-icon-check"></i>
                                <span class="d-inline-block">Attendance List</span>
                            </a>
                        </li>

                        <li class="{{ areActiveRoutes(['attendance']) }}">
                            <a href="{{ route('attendance') }}">
                            <i class="simple-icon-check"></i>
                                <span class="d-inline-block"> Manage Attendance</span>
                            </a>
                        </li>
                    </ul>

                    <ul class="list-unstyled" data-link="courses">
                        @if(in_array('manage_course', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                            <li class="{{ areActiveRoutes(['all-courses', 'course.create','course.edit']) }}">
                                <a href="{{ route('all-courses') }}">
                                    <i class="simple-icon-check"></i>
                                    <span class="d-inline-block">All Courses</span>
                                </a>
                            </li>
                        @endif

                        @if(in_array('manage_divisions', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                            <li class="{{ areActiveRoutes(['all-divisions', 'division.create','division.edit']) }}">
                                <a href="{{ route('all-divisions') }}">
                                    <i class="simple-icon-check"></i>
                                    <span class="d-inline-block">Course Divisions</span>
                                </a>
                            </li>
                        @endif

                        @if(in_array('manage_packages', Auth::user()->permissions) || Auth::user()->user_type == 'super_admin')
                            <li class="{{ areActiveRoutes(['course-packages', 'packages.create','packages.edit']) }}">
                                <a href="{{ route('course-packages') }}">
                                    <i class="simple-icon-check"></i>
                                    <span class="d-inline-block">Course Packages</span>
                                </a>
                            </li>
                        @endif
                        <!-- <li>
                            <a href="Apps.Survey.List.html">
                                <i class="simple-icon-calculator"></i>
                                <span class="d-inline-block">Survey</span>
                            </a>
                        </li> -->
                    </ul>
                </div>
            </div>
        
    </div>


     