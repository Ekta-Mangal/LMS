<form action="{{ route('manageCourse.postadd') }}" method="post" id="addCourse" enctype="multipart/form-data">
    @csrf
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Course Name<span class="text-danger">*</span></label>
                        <input name="title" type="text" class="form-control" id="title"
                            placeholder="Enter Course Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="module_count">No. of Modules<span class="text-danger">*</span></label>
                        <input name="module_count" type="number" class="form-control" id="module_count"
                            placeholder="Enter No. of Modules">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Course Applicable for User<span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" style="width: 100%;" name="level" id="level">
                            <option value="">Select Level</option>
                            <option value="L1">L1 User</option>
                            <option value="L2">L2 User</option>
                            <option value="L3">L3 User</option>
                            <option value="All">All Users</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Publish Date:<span class="text-danger">*</span></label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" name="publish_date" id="publish_date"
                                class="form-control datetimepicker-input" data-target="#reservationdate" />
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $('#addCourse').validate({
            rules: {
                title: {
                    required: true
                },
                module_count: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 9
                },
                level: {
                    required: true
                },
                publish_date: {
                    required: true
                },
            },
            messages: {
                title: {
                    required: "Title is required."
                },
                module_count: {
                    required: "Module count is required.",
                    number: "Module count must be a number.",
                    min: "Module count must be at least 1.",
                    max: "Module count cannot be more than 9."
                },
                level: {
                    required: "Level is required."
                },
                publish_date: {
                    required: "Publish date is required."
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>


<script>
    $(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
        $('#reservationdate').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
