@extends('include.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h1 class="card-title">Users List</h1>
                            </div>
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="manageData" class="table table-bordered">
                                                <thead class="thead-custom">
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Emp ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Location</th>
                                                        <th>Designation</th>
                                                        <th>Role</th>
                                                        <th>Client Name</th>
                                                        <th>Process</th>
                                                        <th>Sub Process</th>
                                                        <th>Reporting Manager</th>
                                                        <th>Badge Level</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($users->isEmpty())
                                                        <tr>
                                                            <td colspan="12" class="text-center">No Users Found</td>
                                                        </tr>
                                                    @else
                                                        @foreach ($users as $index => $user)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $user->empid }}</td>
                                                                <td>{{ $user->name }}</td>
                                                                <td>{{ $user->email }}</td>
                                                                <td>{{ $user->location }}</td>
                                                                <td>{{ $user->designation }}</td>
                                                                <td>{{ $user->role }}</td>
                                                                <td>{{ $user->client_name }}</td>
                                                                <td>{{ $user->process }}</td>
                                                                <td>{{ $user->subprocess }}</td>
                                                                <td>{{ $user->reporting_manager }}</td>
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
            $('#manageData').DataTable({
                pageLength: 50,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csv',
                    text: 'Export Details'
                }],
            });
        });
    </script>
@endsection
