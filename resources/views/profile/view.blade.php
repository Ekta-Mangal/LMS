@extends('include.master')
@section('content')
    <style>
        .badge-img {
            width: 80px;
            height: auto;
        }
    </style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h1 class="card-title">Your Profile</h1>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{ route('profileupdate') }}" method="post" id="editUser"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <!-- Profile Photo -->
                                                    <div class="col-md-3 text-center">
                                                        <div class="position-relative d-inline-block">
                                                            <img src="{{ asset('uploads/profile/' . $user->profile_photo) }}"
                                                                class="img-fluid" alt="Profile Photo" width="150"
                                                                id="profileImage"
                                                                style="border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">

                                                            <!-- Edit Icon -->
                                                            <label for="profile_photo"
                                                                class="position-absolute top-0 end-0 p-2 bg-white rounded-circle shadow"
                                                                style="cursor: pointer;">
                                                                <i class="fa fa-pencil-alt"></i>
                                                            </label>

                                                            <!-- Hidden File Input -->
                                                            <input type="file" name="profile_photo" id="profile_photo"
                                                                class="d-none" onchange="previewImage(event)">
                                                        </div>
                                                    </div>

                                                    <!-- Badge Display Logic -->
                                                    <div class="col-md-3 text-center">
                                                        <div class="position-relative d-inline-block">
                                                            @if (
                                                                $user->badge_level == 'Silver' &&
                                                                    ($user->upgrade_level_status == 'Completed' || $user->upgrade_level_status == 'Waiting'))
                                                                <img src="{{ asset('images/Silver.png') }}"
                                                                    alt="Silver Badge" class="img-fluid badge-img">
                                                            @elseif (
                                                                $user->badge_level == 'Gold' &&
                                                                    ($user->upgrade_level_status == 'Completed' || $user->upgrade_level_status == 'Waiting'))
                                                                <img src="{{ asset('images/Gold.png') }}" alt="Gold Badge"
                                                                    class="img-fluid badge-img">
                                                            @elseif (
                                                                $user->badge_level == 'Platinum' &&
                                                                    ($user->upgrade_level_status == 'Completed' || $user->upgrade_level_status == 'Waiting'))
                                                                <img src="{{ asset('images/Platinum.png') }}"
                                                                    alt="Platinum Badge" class="img-fluid badge-img">
                                                            @endif
                                                        </div>
                                                    </div>


                                                    <!-- Employee ID and Badge Level -->
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input name="id_edit" type="hidden"
                                                                        value="{{ $user->id }}" class="form-control"
                                                                        id="id_edit">
                                                                    <label for="empid">Employee ID</label>
                                                                    <input name="empid" type="text"
                                                                        class="form-control" value="{{ $user->empid }}"
                                                                        id="empid" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="role">Badge Level</label>
                                                                    <input name="role" type="text"
                                                                        class="form-control"
                                                                        value="{{ $user->badge_level }}" id="role"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- Other Details -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="designation">Designation</label>
                                                            <input name="designation" type="text" class="form-control"
                                                                value="{{ $user->designation }}" id="designation" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">Name<span
                                                                    class="text-danger">*</span></label>
                                                            <input name="name" type="text" class="form-control"
                                                                value="{{ $user->name }}" id="name" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Email<span
                                                                    class="text-danger">*</span></label>
                                                            <input name="email" type="email" class="form-control"
                                                                value="{{ $user->email }}" id="email" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password">Password<span
                                                                    class="text-danger">*</span></label>
                                                            <input name="password" type="password" class="form-control"
                                                                id="password" placeholder="Leave Blank For Old Password">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-center">
                                                    <button name="submit" type="submit"
                                                        class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @if (Auth::user()->role !== 'Admin')
                                    <hr>
                                    <!-- Achievements Table -->
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <h3 class="text-center">Achievements</h3>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="thead-custom">
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Course ID</th>
                                                            <th>Course Status</th>
                                                            <th>Module ID</th>
                                                            <th>Module Status</th>
                                                            <th>Completed On</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($achieve->isEmpty())
                                                            <tr>
                                                                <td colspan="6" class="text-center">No Achievements
                                                                    Found
                                                                </td>
                                                            </tr>
                                                        @else
                                                            @foreach ($achieve as $index => $achievement)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $achievement->course_title }}</td>
                                                                    <td>{{ $achievement->course_status ?? 'Pending' }}</td>
                                                                    <td>{{ $achievement->module_title }}</td>
                                                                    <td>{{ $achievement->module_status ?? 'Pending' }}</td>
                                                                    <td>{{ $achievement->completed_on ?? 'N/A' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
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
    <script>
        function previewImage(event) {
            const image = document.getElementById('profileImage');
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
