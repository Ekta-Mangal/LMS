<form action="{{ route('manageModule.update') }}" method="post" id="editModule" enctype="multipart/form-data">
    @csrf
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <input name="id" type="hidden" value="{{ $module->id }}" class="form-control" id="id">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Module Name<span class="text-danger">*</span></label>
                        <input name="title" type="text" class="form-control" id="title"
                            placeholder="Enter Module Name" value="{{ $module->title }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Course In <span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" style="width: 100%;" name="course_id" id="course_id">
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ $module->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pre-Requisite Required<span class="text-danger">*</span></label>
                        <select class="form-control select2bs4" style="width: 100%;" name="prerequisite_required"
                            id="prerequisite_required">
                            <option value="">Select</option>
                            <option value="Yes" {{ $module->prerequisite_required == 'Yes' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="No" {{ $module->prerequisite_required == 'No' ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pre-Requisite Module</label>
                        <select class="form-control select2bs4" style="width: 100%;" name="module_id[]" id="module_id"
                            multiple>
                            <option value="">Select Module</option>
                            @php
                                // Convert comma-separated string into an array
                                $selectedPrerequisites = explode(',', $module['prerequisite_module']);
                            @endphp
                            @foreach ($selectedPrerequisites as $preModuleId)
                                <option value="{{ $preModuleId }}" selected>
                                    Module {{ $preModuleId }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $('#editModule').validate({
            rules: {
                title: {
                    required: true
                },
                course_id: {
                    required: true
                },
                prerequisite_required: {
                    required: true
                },
                "module_id[]": {
                    required: function() {
                        return $('#prerequisite_required').val() === 'Yes';
                    }
                }
            },
            messages: {
                title: {
                    required: "Module Title is required."
                },
                course_id: {
                    required: "Course is required."
                },
                prerequisite_required: {
                    required: "Please select if Pre-requisite is required or not."
                },
                "module_id[]": {
                    required: "Please select at least one Pre-Requisite Module."
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

        $('#prerequisite_required').change(function() {
            if ($(this).val() === 'Yes') {
                $('#module_id').rules('add', {
                    required: true,
                    messages: {
                        required: "Please select at least one Pre-Requisite Module."
                    }
                });
            } else {
                $('#module_id').rules('remove', 'required');
            }
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
                            let isSelected = response.selectedPrerequisites
                                .includes(module.id.toString()) ? 'selected' : '';
                            $('#module_id').append('<option value="' + module.id +
                                '" ' + isSelected + '>' + module.title +
                                '</option>');
                        });
                    }
                });
            }
        });
    });
</script>

<script>
    $(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    });
</script>
