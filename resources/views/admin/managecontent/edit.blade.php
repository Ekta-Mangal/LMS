<form action="{{ route('manageContent.update') }}" method="post" id="editContent" enctype="multipart/form-data">
    @csrf
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <input name="module_id" type="hidden" value="{{ $module_id }}" class="form-control" id="module_id">
                <input name="course_id" type="hidden" value="{{ $course_id }}" class="form-control" id="course_id">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="course_name">Course Name</label>
                        <input type="text" name="course_name" class="form-control" value="{{ $course_title }}"
                            readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="module_name">Module Name</label>
                        <input type="text" name="module_name" class="form-control" value="{{ $module_title }}"
                            readonly>
                    </div>
                </div>
            </div>
            <hr>
            <!-- PDFs Section -->
            <div class="row mb-3">
                <label class="col-12 font-weight-bold text-lg">Uploaded PDFs</label>
                @foreach ($files as $media)
                    @if ($media->type === 'pdf')
                        <div class="col-md-4 d-flex align-items-center">
                            <a href="{{ asset($media->path) }}" target="_blank" class="mr-2">{{ $media->title }}</a>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="removeFile('{{ $media->id }}', 'pdf', this)">
                                Remove
                            </button>
                        </div>
                    @endif
                @endforeach
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3 toggle-btn" data-target="#pdf-upload">+ Add
                PDF</button>
            <div id="pdf-upload" class="row mb-3" style="display: none;">
                <div class="col-md-4">
                    <label>New PDF</label>
                    <input type="file" name="pdf_file" class="form-control">
                </div>
            </div>
            <hr>
            <!-- Audios Section -->
            <div class="row mb-3">
                <label class="col-12 font-weight-bold text-lg">Uploaded Audios</label>
                @foreach ($files as $media)
                    @if ($media->type === 'audio')
                        <div class="col-md-4 d-flex align-items-center">
                            <audio controls>
                                <source src="{{ asset($media->path) }}" type="audio/mpeg">
                            </audio>
                            <button type="button" class="btn btn-danger btn-sm ml-2"
                                onclick="removeFile('{{ $media->id }}', 'audio', this)">Remove</button>
                        </div>
                    @endif
                @endforeach
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3 toggle-btn" data-target="#audio-upload">+ Add
                Audio</button>
            <div id="audio-upload" class="row mb-3" style="display: none;">
                <div class="col-md-4">
                    <label>New Audio</label>
                    <input type="file" name="audio_file" class="form-control">
                </div>
            </div>
            <hr>
            <!-- Videos Section -->
            <div class="row mb-3">
                <label class="col-12 font-weight-bold text-lg">Uploaded Videos</label>
                @foreach ($files as $media)
                    @if ($media->type === 'video')
                        <div class="col-md-6 d-flex align-items-center">
                            <video controls width="100%">
                                <source src="{{ asset($media->path) }}" type="video/mp4">
                            </video>
                            <button type="button" class="btn btn-danger btn-sm ml-2"
                                onclick="removeFile('{{ $media->id }}', 'video', this)">Remove</button>
                        </div>
                    @endif
                @endforeach
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3 toggle-btn" data-target="#video-upload">+ Add
                Video</button>
            <div id="video-upload" class="row mb-3" style="display: none;">
                <div class="col-md-4">
                    <label>New Video</label>
                    <input type="file" name="video_file" class="form-control">
                </div>
            </div>
            <hr>
            <!-- Text Content -->
            <div class="row mb-3">
                <label class="col-12 font-weight-bold text-lg">Uploaded Text</label>
                @foreach ($contents as $textContent)
                    <div class="col-md-6">
                        <input name="text_id" type="hidden" value="{{ $textContent->id }}" class="form-control"
                            id="text_id">
                        <div class="form-group">
                            <label>Edit Text Title <span class="text-danger">*</span></label>
                            <input type="text" name="text_title" class="form-control"
                                value="{{ $textContent->title ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Edit Text Content <span class="text-danger">*</span></label>
                            <textarea name="text_content" class="form-control" rows="4">{{ $textContent->text ?? '' }}</textarea>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm ml-2"
                            onclick="removeFile('{{ $textContent->id }}', 'text', this)">
                            Remove
                        </button>

                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3 toggle-btn" data-target="#content-upload">+ Add
                Content</button>
            <div id="content-upload" class="row mb-3" style="display: none;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>New Text Title <span class="text-danger">*</span></label>
                        <input type="text" name="new_text_title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>New Text Content <span class="text-danger">*</span></label>
                        <textarea name="new_text_content" class="form-control" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('.toggle-btn').on('click', function() {
            let target = $(this).data('target');
            $(target).toggle();
        });
    });

    function removeFile(fileId, fileType, element) {
        if (confirm('Are you sure you want to delete this file?')) {
            $.ajax({
                url: "{{ route('manageContent.removeFile') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: fileType,
                    id: fileId
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
