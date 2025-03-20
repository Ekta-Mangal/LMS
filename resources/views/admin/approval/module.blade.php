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
                                        <h3 class="text-center">Module Completion Requests</h3>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center" id="manageData">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Employee ID</th>
                                                        <th>Employee Name</th>
                                                        <th>Course Name</th>
                                                        <th>Module Name</th>
                                                        <th>Approval Submitted Date</th>
                                                        <th>Acceptance Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($WaitingData as $index => $data)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $data->empid }}</td>
                                                            <td>{{ $data->employee_name }}</td>
                                                            <td>{{ $data->course_name }}</td>
                                                            <td>{{ $data->module_name }}</td>
                                                            <td>{{ $data->submit_for_approval }}</td>
                                                            <td>{{ $data->acceptance_status }}</td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn bg-gradient-success text-white"
                                                                    style="padding: 2px 10px; font-size: 16px;"
                                                                    onclick="accept_module('{{ $data->module_id }}', '{{ $data->empid }}')">
                                                                    Accept
                                                                </button>
                                                                <button type="button"
                                                                    class="btn bg-gradient-danger text-white"
                                                                    style="padding: 2px 10px; font-size: 16px;"
                                                                    onclick="reject_module('{{ $data->module_id }}', '{{ $data->empid }}')">
                                                                    Decline
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center">No Requests Found</td>
                                                        </tr>
                                                    @endforelse
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
                responsive: true,
                buttons: [{
                    extend: 'csv',
                    text: 'Export Details'
                }],
            });
        });

        function accept_module(module_id, empid) {
            $.ajax({
                url: "{{ route('module_completetion_accept') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "module_id": module_id,
                    "empid": empid
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                }
            });
        }

        function reject_module(module_id, empid) {
            $.ajax({
                url: "{{ route('module_completetion_reject') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "module_id": module_id,
                    "empid": empid
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                }
            });
        }
    </script>
@endsection
