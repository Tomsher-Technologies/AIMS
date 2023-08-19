    <div class="data_card w-100">
        <div class="table-responsive">
            
                <input type="hidden" name="class_id" id="class_id" value="{{ $class_id }}">
                <input type="hidden" name="date_value" id="date_value" value="{{ $date }}">
                <input type="hidden" name="course_id" id="course_id" value="{{ $course }}">
                <input type="hidden" name="division_id" id="division_id" value="{{ $course_division }}">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th scope="col" class="w-10">Sl No</th>
                            <th scope="col" class="w-50">Student Name</th>
                            <th scope="col" class="w-30">Student Code</th>
                            <th scope="col" class="w-10 text-center">Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($students[0]))
                            @foreach($students as $key => $stud)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $stud->name }}</td>
                                    <td>{{ $stud->unique_id }}</td>
                                
                                    <td class="text-center">
                                        <input type="checkbox" class="wh-17" name="attendance[{{$stud->user_id}}]" id="attendance"  value="" @if($stud->is_attended == 1) checked @endif>
                                    </td>
                                
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-right">
                                    <button type="submit" id="save-attend" class="btn btn-success"> Save Attendance</button>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4" class="text-center">No data found.</td>
                            </tr>
                        @endif
                        
                    </tbody>
                </table>
           
        </div>
    </div>
    @section('footer')
    <script>
       
    </script>
    @endsection