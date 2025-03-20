@extends('include.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ol class="breadcrumb float-sm-left">
                                            <button type="button"
                                                class="btn btn-block bg-gradient-primary text-white form-control"
                                                data-inline="true" data-toggle="modal" onclick="add()"
                                                data-target="#addModal"><i class="fa fa-plus icon-white"></i> Add Module
                                            </button>
                                        </ol>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="manageModuleData" class="table table-bordered text-center">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Title</th>
                                                        <th>Course</th>
                                                        <th>Pre-Requisite Required</th>
                                                        <th>Pre-Requisite Module</th>
                                                        <th>Module Created By</th>
                                                        <th>Module Created On</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($Modules->isEmpty())
                                                        <tr>
                                                            <td colspan="16" class="text-center">No Modules Found
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($Modules as $index => $module)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $module->title }}</td>
                                                                <td>{{ $module->course_name }}</td>
                                                                <td>{{ $module->prerequisite_required }}</td>
                                                                <td>{{ $module->prerequisite_module_titles }}</td>
                                                                <td>{{ $module->created_by_name }}</td>
                                                                <td>{{ $module->created_at }}</td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn bg-gradient-primary text-white"
                                                                        style="padding: 2px 5px; font-size: 16px; margin-top:3px;"
                                                                        onclick="editModule('{{ $module->id }}')">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn bg-gradient-danger text-white"
                                                                        style="padding: 2px 5px; font-size: 16px; margin-top:3px;"
                                                                        onclick="deleteModule('{{ $module->id }}')">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </td>
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
    <script>
        function add() {
            $.ajax({
                type: "get",
                url: "{{ route('manageModule.add') }}",
                success: function(data) {
                    console.log(data.url);
                    $("#addModal").modal('show');
                    $('#addbody').html(data.html);
                }
            });
        };
    </script>

    <div id="addModal" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white ">
                    <h4 class="modal-title">Enter Module Details Here:-</h4>
                    <button type="button" style="color: #ffffff" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="addbody">
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#manageModuleData').DataTable({
                scrollX: true
            });
        });
    </script>

    <script>
        function editModule(id) {
            $.ajax({
                type: "get",
                url: "{{ route('manageModule.edit') }}",
                data: {
                    "id": id
                },
                success: function(data) {
                    console.log(data.url);
                    $("#editModal").modal('show');
                    $('#editbody').html(data.html);
                }
            });
        };
    </script>

    <div id="editModal" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">Edit Module Details Here:-</h4>
                    <button type="button" style="color: #ffffff" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="editbody">
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteModule(id) {
            var result = confirm('Do you want to Delete this Module');
            if (result == true) {
                $.ajax({
                    url: "{{ route('manageModule.delete') }}",
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success: function(response) {
                        console.log(response.status);
                        if (response.status == 'success') {
                            toastr.success(response.message);
                            setTimeout(() => {
                                location.reload(true);
                            }, 2000);
                        }
                        if (response.status == 'error') {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        }
    </script>
@endsection
