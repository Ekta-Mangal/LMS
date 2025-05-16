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
                            <div class="card-header">
                                <h1 class="card-title">Learning Hub Dashboard</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-body">
                                            @if (Auth::user()->role !== 'Admin')
                                                <div class="row justify-content-center">
                                                    <div class="col-md-12 text-center">
                                                        <h5>Hey <strong>{{ Auth::user()->name }}</strong>, Welcome to
                                                            QMS Learning Platform</h5>
                                                        @php
                                                            $userRole = $role;
                                                        @endphp

                                                        <p>Kindly click on the 'Enroll Course' option below to start</p>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    @foreach ($Courses as $course)
                                                        @php
                                                            $level = $course->level;
                                                            $title = $course->title;
                                                            $id = $course->id;
                                                        @endphp

                                                        <div class="card card-outline">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <span class="level-title">{{ $title }}</span>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <span class="d-flex align-items-center">
                                                                            @if ($role == $level)
                                                                                @if (in_array($id, $enrolledCourses))
                                                                                    @if (isset($courseStatuses[$id]) && $courseStatuses[$id] === 'Completed')
                                                                                        <button type="button"
                                                                                            class="btn bg-success text-white form-control btn-spacing"
                                                                                            disabled>
                                                                                            Course Completed
                                                                                        </button>
                                                                                    @else
                                                                                        <button type="button"
                                                                                            class="btn bg-success text-white form-control btn-spacing"
                                                                                            disabled>
                                                                                            Already Enrolled
                                                                                        </button>
                                                                                        <button type="button"
                                                                                            class="btn bg-gradient-primary text-white form-control btn-spacing"
                                                                                            data-toggle="modal"
                                                                                            onclick="viewcourse({{ $id }})"
                                                                                            data-target="#viewcourse">
                                                                                            VIEW COURSE DETAILS
                                                                                        </button>
                                                                                    @endif
                                                                                @else
                                                                                    <i
                                                                                        class="fas fa-lock-open text-warning lock-icon"></i>
                                                                                    <button type="button"
                                                                                        onclick="enroll({{ $id }})"
                                                                                        class="btn bg-gradient-primary text-white form-control">
                                                                                        ENROLL COURSE
                                                                                    </button>
                                                                                    <button type="button"
                                                                                        class="btn bg-gradient-primary text-white form-control btn-spacing"
                                                                                        data-toggle="modal"
                                                                                        onclick="viewcourse({{ $id }})"
                                                                                        data-target="#viewcourse">
                                                                                        VIEW COURSE DETAILS
                                                                                    </button>
                                                                                @endif
                                                                            @else
                                                                                @if (isset($courseStatuses[$id]) && $courseStatuses[$id] === 'Completed')
                                                                                    <button type="button"
                                                                                        class="btn bg-success text-white form-control btn-spacing"
                                                                                        disabled>
                                                                                        Course Completed
                                                                                    </button>
                                                                                @else
                                                                                    <i
                                                                                        class="fas fa-lock text-dark lock-icon"></i>
                                                                                    <button type="button"
                                                                                        class="btn bg-gradient-primary text-white form-control"
                                                                                        disabled>
                                                                                        ENROLL COURSE
                                                                                    </button>
                                                                                    <button type="button"
                                                                                        class="btn bg-gradient-primary text-white form-control btn-spacing"
                                                                                        disabled>
                                                                                        VIEW COURSE DETAILS
                                                                                    </button>
                                                                                @endif
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row text-center">
                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-olive"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-purple" id="course_enrollment"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($distinctUserCount))
                                                                            {{ $distinctUserCount }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-database"></i> Course Enrollments
                                                                </a>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-danger"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-warning" id="completed_course"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($completedUsersCount))
                                                                            {{ $completedUsersCount }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-check-circle"></i> Users Completed
                                                                    Courses
                                                                </a>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-primary"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-danger" id="attempting_course"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($inProgressUsersCount))
                                                                            {{ $inProgressUsersCount }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-spinner"></i> Users Attempting
                                                                    Courses
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row text-center mt-3">
                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-secondary"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-info" id="silver_badge"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($silverCount))
                                                                            {{ $silverCount }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-medal"></i> Users With Silver
                                                                    Badges
                                                                </a>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-warning"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-blue" id="gold_badge"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($goldCount))
                                                                            {{ $goldCount }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-award"></i> Users With Gold Badges
                                                                </a>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-purple"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-light" id="platinum_badge"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($platinumCount))
                                                                            {{ $platinumCount }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-trophy"></i> Users With Platinum
                                                                    Badges
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <!-- New Badge Row for Eligible Users -->
                                                        <div class="row text-center mt-3">
                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-secondary"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-info" id="silver_eligible"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($silverEligible))
                                                                            {{ $silverEligible }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-user-check"></i> Silver Eligible
                                                                    Users
                                                                </a>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-warning"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-blue" id="gold_eligible"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($goldEligible))
                                                                            {{ $goldEligible }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-user-check"></i> Gold Eligible
                                                                    Users
                                                                </a>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <a class="btn btn-app bg-purple"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-light" id="platinum_eligible"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($platinumEligible))
                                                                            {{ $platinumEligible }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-user-check"></i> Platinum Eligible
                                                                    Users
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div class="row text-center mt-3">
                                                            <div class="col-md-4 offset-md-4">
                                                                <a class="btn btn-app bg-orange"
                                                                    style="font-size: 24px; padding: 20px; width: 250px; height: 100px;">
                                                                    <span class="badge bg-olive" id="session_pending"
                                                                        style="font-size: 18px; padding: 15px 15px;">
                                                                        @if (isset($pendingApprovalCounts))
                                                                            {{ $pendingApprovalCounts }}
                                                                        @endif
                                                                    </span>
                                                                    <i class="fas fa-hourglass-half"></i> Session
                                                                    Approvals Pending
                                                                </a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function enroll(id) {
            $.ajax({
                url: "{{ route('enrollagent') }}",
                type: 'GET',
                data: {
                    "id": id
                },
                success: function(data) {
                    if (data.success) {
                        $("#viewcourseModal").modal('hide');
                        $("#enrollModal").modal('show');
                        $('#msgBody').html(data.html);
                    } else {
                        toastr.error(data.error);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong. Please try again later.');
                }
            });
        }
    </script>

    <script>
        function viewcourse(id) {
            $.ajax({
                type: "get",
                url: "{{ route('coursedetails') }}",
                data: {
                    "id": id
                },
                success: function(data) {
                    $("#viewcourseModal").modal('show');
                    $('#courseBody').html(data.html);
                }
            });
        };
    </script>

    <div id="enrollModal" class="modal fade">
        <div class="card-body" id="msgBody">
        </div>
    </div>

    <div id="viewcourseModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white ">
                    <h4 class="modal-title">Course Details :-</h4>
                    <button type="button" style="color: #000000" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                </div>
                <div class="card-body" id="courseBody">
                </div>
            </div>
        </div>
    </div>
@endsection
