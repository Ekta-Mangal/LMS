@extends('include.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h1 class="card-title">Content Management</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ol class="breadcrumb float-sm-left">
                                            <button type="button"
                                                class="btn btn-block bg-gradient-primary text-white form-control"
                                                data-inline="true" data-toggle="modal" onclick="add()"
                                                data-target="#addModal"><i class="fa fa-plus icon-white"></i> Add Content
                                            </button>
                                        </ol>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="manageContentData" class="table table-bordered text-center">
                                                <thead class="thead-custom">
                                                    <tr>
                                                        <th>S.No.</th>
                                                        <th>Course Name</th>
                                                        <th>Module Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($Contents->isEmpty())
                                                        <tr>
                                                            <td colspan="9" class="text-center">No Content Found</td>
                                                        </tr>
                                                    @else
                                                        @php $index = 1; @endphp
                                                        @foreach ($Contents->groupBy('module_id') as $module)
                                                            @foreach ($module as $key => $content)
                                                                <tr>
                                                                    <td>{{ $index++ }}</td>
                                                                    <td>{{ $content->course_name }}</td>
                                                                    <td>{{ $content->module_name }}</td>
                                                                    <td>
                                                                        @if ($content->prerequisite_required === 'Yes')
                                                                            <button type="button"
                                                                                class="btn bg-gradient-primary text-white"
                                                                                style="padding: 2px 5px; font-size: 16px;margin-top:3px;"
                                                                                onclick="editQuizContent('{{ $content->module_id }}')">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn bg-gradient-danger text-white"
                                                                                style="padding: 2px 5px; font-size: 16px; margin-top:3px;"
                                                                                onclick="deleteQuizContent('{{ $content->module_id }}')">
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </button>
                                                                        @else
                                                                            <button type="button"
                                                                                class="btn bg-gradient-primary text-white"
                                                                                style="padding: 2px 5px; font-size: 16px;margin-top:3px;"
                                                                                onclick="editContent('{{ $content->module_id }}')">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn bg-gradient-danger text-white"
                                                                                style="padding: 2px 5px; font-size: 16px; margin-top:3px;"
                                                                                onclick="deleteContent('{{ $content->module_id }}')">
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
        $(document).ready(function() {
            $('#manageContentData').DataTable();
        });
    </script>

    <script>
        function add() {
            $.ajax({
                type: "get",
                url: "{{ route('manageContent.add') }}",
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
                    <h4 class="modal-title">Enter Content Details Here:-</h4>
                    <button type="button" style="color: #000000" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="addbody">
                </div>
            </div>
        </div>
    </div>

    <script>
        function editContent(module_id) {
            $.ajax({
                type: "get",
                url: "{{ route('manageContent.edit') }}",
                data: {
                    "module_id": module_id
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
                    <h4 class="modal-title">Edit Content Details Here:-</h4>
                    <button type="button" style="color: #000000" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="editbody">
                </div>
            </div>
        </div>
    </div>

    <script>
        function editQuizContent(module_id) {
            $.ajax({
                type: "get",
                url: "{{ route('manageContent.editquiz') }}",
                data: {
                    "module_id": module_id
                },
                success: function(data) {
                    console.log(data.url);
                    $("#editQuizModal").modal('show');
                    $('#editQuizbody').html(data.html);
                }
            });
        };
    </script>

    <div id="editQuizModal" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">Edit Quiz Content Details Here:-</h4>
                    <button type="button" style="color: #000000" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="editQuizbody">
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteContent(module_id) {
            var result = confirm('All the content in this module will be deleted ?');
            if (result) {
                $.ajax({
                    url: "{{ route('manageContent.delete') }}",
                    type: 'GET',
                    data: {
                        "module_id": module_id
                    },
                    success: function(response) {
                        console.log(response.status);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            setTimeout(() => {
                                location.reload(true);
                            }, 2000);
                        } else if (response.status === 'error') {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        }
    </script>

    <script>
        function deleteQuizContent(module_id) {
            var result = confirm('All the Quiz and Questions in this module will be deleted ?');
            if (result) {
                $.ajax({
                    url: "{{ route('manageContent.deleteQuiz') }}",
                    type: 'GET',
                    data: {
                        "module_id": module_id
                    },
                    success: function(response) {
                        console.log(response.status);
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            setTimeout(() => {
                                location.reload(true);
                            }, 2000);
                        } else if (response.status === 'error') {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        }
    </script>
@endsection
