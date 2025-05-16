@extends('include.master')
@section('content')
    <style>
        .level-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .lock-icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }

        .btn-spacing {
            margin-left: 10px;
        }
    </style>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 text-center">
                                        <h5><b>Level 1 - Silver Certification QMS</b></h5>
                                    </div>
                                </div>
                                @if ($modules->isNotEmpty())
                                    @foreach ($modules as $module)
                                        <div class="mt-4">
                                            <div class="card card-outline">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <span class="level-title">Module : {{ $module->id }} -
                                                                {{ $module->title }}</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <span class="d-flex align-items-center">
                                                                @if ($module->isLocked)
                                                                    <i class="fas fa-lock text-dark lock-icon"></i>
                                                                    <button type="button"
                                                                        class="btn bg-gradient-primary text-white form-control btn-spacing"
                                                                        disabled>
                                                                        Locked (Complete Prerequisites)
                                                                    </button>
                                                                @else
                                                                    @if ($module->approval_status === 'Pending')
                                                                        <i
                                                                            class="fas fa-lock-open text-warning lock-icon"></i>
                                                                        <button type="button"
                                                                            class="btn bg-gradient-primary text-white form-control btn-spacing"
                                                                            onclick="viewL1Module({{ $module->id }})"
                                                                            data-toggle="modal" data-target="#viewcourse">
                                                                            View Module
                                                                        </button>
                                                                    @elseif ($module->approval_status === 'Waiting')
                                                                        <button type="button"
                                                                            class="btn bg-gradient-secondary text-white form-control btn-spacing"
                                                                            disabled>
                                                                            Request Submitted for Approval
                                                                        </button>
                                                                    @elseif ($module->approval_status === 'Approved')
                                                                        <button type="button"
                                                                            class="btn bg-gradient-success text-white form-control btn-spacing"
                                                                            disabled>
                                                                            Completed
                                                                        </button>
                                                                    @elseif ($module->approval_status === 'Declined')
                                                                        <button type="button"
                                                                            class="btn bg-gradient-danger text-white form-control btn-spacing"
                                                                            disabled>
                                                                            Rejected
                                                                        </button>
                                                                        @if ($module->module_status === 'Waiting')
                                                                            <button type="button"
                                                                                class="btn bg-gradient-secondary text-white form-control btn-spacing"
                                                                                disabled>
                                                                                Wait for Approval
                                                                            </button>
                                                                        @else
                                                                            <button type="button"
                                                                                class="btn bg-gradient-primary text-white form-control btn-spacing"
                                                                                onclick="viewL1Module({{ $module->id }})">
                                                                                Re-Attempt
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card-body">
                                        <div class="row justify-content-center">
                                            <div class="col-md-10">
                                                <!-- Enrollment Required Message Card -->
                                                <div class="card border-0 shadow-lg text-center mx-auto"
                                                    style="max-width: 600px; background-color: #ffebeb; border-radius: 15px;">
                                                    <div class="card-body py-5">
                                                        <h4 class="font-weight-bold text-danger mb-3">🚨 You are Not
                                                            Enrolled in the Course!</h4>
                                                        <p class="text-dark font-weight-medium mb-2">
                                                            Please <strong>enroll first</strong> to access the course
                                                            content.
                                                        </p>
                                                        <p class="text-dark font-weight-medium">
                                                            The <strong>{{ $badge }} Badge</strong> and
                                                            <strong>Certification of
                                                                Completion</strong> will be available after enrollment.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($NewCourseUnlocked)
                            <div class="card card-primary card-outline" id="congratsCard">
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12 text-center">
                                            <!-- Congratulations Message Card -->
                                            <div class="card border-secondary mx-auto w-75"
                                                style="background-color: #ffffe6;">
                                                <div class="card-body py-4">
                                                    <p class="h4 font-weight-bold text-dark">
                                                        🎉 Congratulations!! 🎉<br>
                                                        You have passed the Assessment.
                                                    </p>
                                                    <p class="text-dark font-weight-medium">
                                                        You have earned the <strong>Silver Badge</strong> and your
                                                        certificate of
                                                        Completion has been unlocked!
                                                    </p>
                                                </div>
                                            </div>
                                            @if ($upgrade === 'Waiting')
                                                <div class="card border-info mx-auto w-75 mt-4"
                                                    style="background-color: #f0f7ff;">
                                                    <div class="card-body py-4">
                                                        <p class="h4 font-weight-bold text-dark">
                                                            🎯 You are eligible for L2 Course on QMS.
                                                        </p>
                                                        <!-- Buttons in one row with proper gap -->
                                                        <div class="d-flex justify-content-center mt-3">
                                                            <button type="button"
                                                                class="btn bg-gradient-secondary text-white">
                                                                Requested for Upgrade - Wait for Approval.
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($upgrade === 'Declined')
                                                <div class="card border-danger mx-auto w-75 mt-4"
                                                    style="background-color: #ffe6e6;">
                                                    <div class="card-body py-4">
                                                        <p class="h4 font-weight-bold text-dark">
                                                            ❌ Your Upgrade Request for L2 Course on QMS & ITS Application
                                                            has been Rejected.
                                                        </p>
                                                        <!-- Buttons in one row with proper gap -->
                                                        <div class="d-flex justify-content-center mt-3">
                                                            <button type="button" class="btn bg-gradient-danger text-white"
                                                                disabled>
                                                                Upgrade Request Rejected
                                                            </button>
                                                            <button type="button"
                                                                class="btn bg-gradient-primary text-white ml-3"
                                                                onclick="upgrade_level()">
                                                                Reapply for Upgrade
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Upgrade Message Card -->
                                                <div class="card border-info mx-auto w-75 mt-4"
                                                    style="background-color: #f0f7ff;">
                                                    <div class="card-body py-4">
                                                        <p class="h4 font-weight-bold text-dark">
                                                            🎯 You are eligible for L2 Course on QMS.
                                                        </p>
                                                        <p class="font-weight-bold text-dark">
                                                            Click <span class="text-primary">'Upgrade'</span> below to
                                                            Start
                                                            your
                                                            course.
                                                        </p>

                                                        <!-- Buttons in one row with proper gap -->
                                                        <div class="d-flex justify-content-center mt-3">
                                                            <button type="button"
                                                                class="btn bg-gradient-primary text-white btn-sm px-3 mx-2"
                                                                onclick="window.location.href='{{ route('certificate') }}'">
                                                                View Certificate
                                                            </button>
                                                            <button type="button"
                                                                class="btn bg-gradient-primary text-white btn-sm px-3 mx-2"
                                                                onclick="upgrade_level()">
                                                                Upgrade
                                                            </button>
                                                            <button type="button"
                                                                class="btn bg-gradient-primary text-white btn-sm px-3 mx-2"
                                                                id="hideCongratsCard">
                                                                Not Now
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewL1Module(id) {
            $.ajax({
                type: "GET",
                url: "{{ route('moduleDetails') }}",
                data: {
                    "id": id
                },
                success: function(data) {
                    $("#viewL1ModuleModal").modal('show');
                    $('#ModuleBody').html(data.html);
                }
            });
        }
    </script>

    <div id="viewL1ModuleModal" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white ">
                    <h4 class="modal-title">Module Details :-</h4>
                    <button type="button" style="color: #000000" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="ModuleBody"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#hideCongratsCard").on("click", function() {
                $("#congratsCard").hide();
            });
        });
    </script>

    <script>
        function upgrade_level() {
            $.ajax({
                url: "{{ route('upgrade_level') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Upgrade request submitted successfully. Waiting for approval.');
                    } else {
                        toastr.error(response.message);
                    }
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    toastr.error('Something went wrong. Please try again.');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            });
        }
    </script>

    <script>
        function reattemptmodule(id) {
            $.ajax({
                type: "GET",
                url: "{{ route('module.reattempt') }}",
                data: {
                    "id": id
                },
                success: function(data) {
                    $('#viewL1ModuleModal').modal('hide');
                    $('#ContentBody').html(data.html);
                    $('#ContentModuleModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');
                }
            });
        }
    </script>

    <script>
        function start(id) {
            $.ajax({
                type: "GET",
                url: "{{ route('startmodule') }}",
                data: {
                    "id": id
                },
                success: function(data) {
                    $('#viewL1ModuleModal').modal('hide');
                    $('#ContentBody').html(data.html);
                    $('#ContentModuleModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');
                }
            });
        }
    </script>

    <!-- Content Module Modal -->
    <div id="ContentModuleModal" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title">Module Study Material :-</h4>
                    <button type="button" style="color: #000000" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body" id="ContentBody"></div>
            </div>
        </div>
    </div>
@endsection
