    <div class="menu">
        <div class="main-menu">
            <div class="scroll">
                <ul class="list-unstyled">
                    <li class="{{ areActiveRoutes(['admin.dashboard']) }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="iconsminds-shop-4"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ areActiveRoutes(['all-courses', 'course.create','course.edit']) }}">
                        <a href="{{ route('all-courses') }}">
                            <i class="iconsminds-digital-drawing"></i> Courses
                        </a>
                    </li>
                    <li class="{{ areActiveRoutes(['all-divisions', 'division.create','division.edit']) }}">
                        <a href="{{ route('all-divisions') }}">
                            <i class="iconsminds-digital-drawing"></i> Course Divisions
                        </a>
                    </li>
                    <li class="{{ areActiveRoutes(['course-packages', 'packages.create','packages.edit']) }}">
                        <a href="{{ route('course-packages') }}">
                            <i class="iconsminds-digital-drawing"></i> Course Packages
                        </a>
                    </li>
                    <li class="{{ areActiveRoutes(['classes', 'class.create','class.edit']) }}">
                        <a href="{{ route('classes') }}">
                            <i class="iconsminds-digital-drawing"></i> Classes
                        </a>
                    </li>
                    <li class="{{ areActiveRoutes(['teachers', 'teacher.create','teacher.edit']) }}">
                        <a href="{{ route('teachers') }}">
                            <i class="iconsminds-conference"></i> Teachers
                        </a>
                    </li>
                    <li class="{{ areActiveRoutes(['assign-teachers', 'assign-teacher.create','assign-teacher.edit']) }}">
                        <a href="{{ route('assign-teachers') }}">
                            <i class="iconsminds-conference"></i>Assign Teachers
                        </a>
                    </li>

                    <li class="{{ areActiveRoutes(['student.bookings']) }}">
                        <a href="{{ route('student.bookings') }}">
                            <i class="iconsminds-conference"></i> Student Bookings
                        </a>
                    </li>

                    <li class="{{ areActiveRoutes(['mock-tests']) }}">
                        <a href="{{ route('mock-tests') }}">
                            <i class="iconsminds-pen"></i>Mock Test Results
                        </a>
                    </li>

                    <li class="{{ areActiveRoutes(['attendance-list']) }}">
                        <a href="{{ route('attendance-list') }}">
                            <i class="iconsminds-conference"></i>Attendance List
                        </a>
                    </li>
                    <li class="{{ areActiveRoutes(['attendance']) }}">
                        <a href="{{ route('attendance') }}">
                            <i class="iconsminds-conference"></i> Manage Attendance
                        </a>
                    </li>

                    <li class="{{ areActiveRoutes(['students', 'student.create','student.edit']) }}">
                        <a href="{{ route('students') }}">
                            <i class="iconsminds-conference"></i> Students
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
    </div>