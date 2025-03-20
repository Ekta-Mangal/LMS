<form action="{{ route('manageContent.postadd') }}" method="post" id="addContent" enctype="multipart/form-data">
    @csrf
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="course_id">Select Course<span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" data-dropdown-css-class="select2-danger"
                            style="width: 100%;" name="course_id" id="course_id">
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="module_id">Select Module<span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" data-dropdown-css-class="select2-danger"
                            style="width: 100%;" name="module_id" id="module_id">
                            <option value="">Select Module</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Select Content Type<span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" style="width: 100%;" name="content_type"
                            id="content_type">
                            <option value="">Select Content Type</option>
                            <option value="pdf">PDF</option>
                            <option value="audio">Audio</option>
                            <option value="video">Video</option>
                            <option value="text">Text</option>
                            <option value="quiz">Quiz</option>
                        </select>
                    </div>
                </div>

                <!-- PDF Upload -->
                <div class="col-md-12 content-upload" id="pdf_upload" style="display: none;">
                    <div class="form-group">
                        <label>Upload PDF<span class="text-danger">*</span></label>
                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                    </div>
                </div>

                <!-- Audio Upload -->
                <div class="col-md-12 content-upload" id="audio_upload" style="display: none;">
                    <div class="form-group">
                        <label>Upload Audio File<span class="text-danger">*</span></label>
                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                    </div>
                </div>

                <!-- Video Upload -->
                <div class="col-md-12 content-upload" id="video_upload" style="display: none;">
                    <div class="form-group">
                        <label>Upload Video File<span class="text-danger">*</span></label>
                        <input type="file" name="video_file" class="form-control" accept="video/*">
                    </div>
                </div>

                <!-- Text Content -->
                <div class="col-md-12 content-upload" id="text_upload" style="display: none;">
                    <div class="form-group">
                        <label>Enter Text Title <span class="text-danger">*</span></label>
                        <input type="text" name="text_title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Enter Text Content <span class="text-danger">*</span></label>
                        <textarea name="text_content" class="form-control" rows="4"></textarea>
                    </div>
                </div>

                <!-- Quiz Upload -->
                <div class="col-md-12 content-upload" id="quiz_upload" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Enter Quiz Title <span class="text-danger">*</span></label>
                                <input type="text" name="quiz_title" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Enter Quiz Description <span class="text-danger">*</span></label>
                                <textarea name="quiz_description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Passing Marks <span class="text-danger">*</span></label>
                                <input type="number" name="passing_marks" class="form-control"
                                    oninput="this.value = this.value.slice(0, 2)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Allowed Attempts <span class="text-danger">*</span></label>
                                <input type="text" name="allow_attempts" class="form-control"
                                    oninput="this.value = this.value.slice(0, 1)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload Quiz File <span class="text-danger">*</span></label>
                                <input name="file" type="file" class="form-control" id="file"
                                    placeholder="Upload File">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="margin-top: 35px">
                                <a id="format-link" href="{{ asset('uploads/format/Quiz_Format.csv') }}">
                                    <i class="fa fa-download" style="color: black" aria-hidden="true"></i>
                                    Download Format</a>
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
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    });

    $('#course_id').change(function() {
        let courseId = $(this).val();
        $('#module_id').empty().append('<option value="">Select Module</option>');
        if (courseId) {
            $.ajax({
                url: '{{ route('getModulesByCourse') }}',
                type: 'GET',
                data: {
                    course_id: courseId
                },
                success: function(response) {
                    $.each(response.modules, function(index, module) {
                        $('#module_id').append('<option value="' + module.id + '">' + module
                            .title + '</option>');
                    });
                }
            });
        }
    });

    $('#content_type').change(function() {
        let contentType = $(this).val();
        $('.content-upload').hide().find('input, textarea, select').prop('required', false);

        if (contentType) {
            $('#' + contentType + '_upload').show().find('input, textarea, select').prop('required', true);
        }
    });

    $(function() {
        $('#addContent').validate({
            rules: {
                course_id: {
                    required: true
                },
                module_id: {
                    required: true
                },
                content_type: {
                    required: true
                }
            },
            messages: {
                course_id: {
                    required: "Select a Course."
                },
                module_id: {
                    required: "Select a Module."
                },
                content_type: {
                    required: "Select Content Type."
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
