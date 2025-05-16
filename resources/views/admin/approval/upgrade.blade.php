@extends('include.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h1 class="card-title">Level Upgrade Requests</h1>
                            </div>
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center" id="manageData">
                                                <thead class="thead-custom">
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Employee ID</th>
                                                        <th>Employee Name</th>
                                                        <th>Designation</th>
                                                        <th>Level</th>
                                                        <th>Process</th>
                                                        <th>Sub Process</th>
                                                        <th>Reporting Manager</th>
                                                        <th>Badge Level</th>
                                                        <th>Approval Submitted Date</th>
                                                        <th>Upgrade</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($users as $index => $data)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $data->empid }}</td>
                                                            <td>{{ $data->name }}</td>
                                                            <td>{{ $data->designation }}</td>
                                                            <td>{{ $data->role }}</td>
                                                            <td>{{ $data->process }}</td>
                                                            <td>{{ $data->subprocess }}</td>
                                                            <td>{{ $data->reporting_manager }}</td>
                                                            <td>{{ $data->badge_level }}</td>
                                                            <td>{{ $data->submit_for_approval }}</td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn bg-gradient-success text-white"
                                                                    style="padding: 2px 10px; font-size: 16px;"
                                                                    onclick="acceptUpgrade('{{ $data->empid }}')">
                                                                    Approve
                                                                </button>
                                                                <button type="button"
                                                                    class="btn bg-gradient-danger text-white"
                                                                    style="padding: 2px 10px; font-size: 16px;margin-top: 10px;"
                                                                    onclick="rejectUpgrade('{{ $data->empid }}')">
                                                                    Decline
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="16" class="text-center">No Requests Found</td>
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
            $('#manageData').DataTable({
                dom: 'Bfrtip',
                scrollX: true,
                buttons: [{
                    extend: 'csv',
                    text: 'Export Details'
                }],
            });
        });

        function acceptUpgrade(empid) {
            var result = confirm('Do you want to accept the user request for level upgrade?');
            if (result == true) {
                $.ajax({
                    type: "get",
                    url: "{{ route('upgradedetails') }}",
                    data: {
                        "empid": empid
                    },
                    success: function(data) {
                        $("#viewUpgradeModal").modal('show');
                        $('#UpgradeBody').html(data.html);
                    }
                });
            }
        };

        function rejectUpgrade(empid) {
            var result = confirm('Do you want to decline the user request for level upgrade?');
            if (result == true) {
                $.ajax({
                    url: "{{ route('level_upgrade_reject') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "empid": empid
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                    }
                });
            }
        };
    </script>

    <div id="viewUpgradeModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white ">
                    <h4 class="modal-title">Upgrade User Details :-</h4>
                    <button type="button" style="color: #000000" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="UpgradeBody">
                </div>
            </div>
        </div>
    </div>
@endsection
