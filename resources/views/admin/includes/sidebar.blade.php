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
                    <li class="{{ areActiveRoutes(['course-packages']) }}">
                        <a href="{{ route('course-packages') }}">
                            <i class="iconsminds-digital-drawing"></i> Course Packages
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="iconsminds-conference"></i> Users
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
    </div>