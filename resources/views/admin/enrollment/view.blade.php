@extends('include.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="manageData" class="table table-bordered">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Emp ID</th>
                                                        <th>Emp Name</th>
                                                        <th>Location</th>
                                                        <th>Designation</th>
                                                        <th>Process</th>
                                                        <th>Sub Process</th>
                                                        <th>Reporting Manager</th>
                                                        <th>Usertype</th>
                                                        <th>Course Enrolled Name</th>
                                                        <th>Course Enrollement Date</th>
                                                        <th>Course Status</th>
                                                        <th>Course Completetion Date</th>
                                                        <th>Quiz Name</th>
                                                        <th>Assessment Scoring</th>
                                                        <th>Badge Achieved</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($users->isEmpty())
                                                        <tr>
                                                            <td colspan="16" class="text-center">No Users Found
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($users as $index => $user)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $user->empid }}</td>
                                                                <td>{{ $user->name }}</td>
                                                                <td>{{ $user->location }}</td>
                                                                <td>{{ $user->designation }}</td>
                                                                <td>{{ $user->process }}</td>
                                                                <td>{{ $user->subprocess }}</td>
                                                                <td>{{ $user->reporting_manager }}</td>
                                                                <td>{{ $user->role }}</td>
                                                                <td>{{ $user->course_title }}</td>
                                                                <td>{{ $user->created_at ?? 'N/A' }}</td>
                                                                <td>{{ $user->course_status }}</td>
                                                                <td>{{ $user->completed_on ?? 'N/A' }}</td>
                                                                <td>{{ $user->quiz_title ?? 'N/A' }}</td>
                                                                <td>{{ $user->score ?? 'N/A' }}</td>
                                                                <td>{{ $user->badge_level }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            var table = $('#manageData').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csv',
                    text: 'Export Details'
                }],
            });
        });
    </script>
@endsection
