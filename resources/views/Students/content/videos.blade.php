<div class="card card-primary card-outline"
    style="border: 1px solid #ccc; padding: 10px; max-height: 80vh; overflow-y: auto;">
    <div class="card-body">
        <div style="border: 1px solid #ccc; padding: 15px;">
            @if ($contents->isNotEmpty())

                <!-- Video Section -->
                <h3 style="text-align: center; font-weight: bold; margin-bottom: 15px;">Audios & Videos</h3>
                <ul style="list-style: none; padding: 0; font-size: 16px; margin: 0;">
                    @foreach ($contents as $content)
                        @if ($content->type === 'video')
                            <li style="margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
                                <!-- Video Title & Button -->
                                <div
                                    style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px;">
                                    <strong style="flex: 1; min-width: 150px;">{{ $content->title }}</strong>

                                    @if ($content->watch_status === 'Pending')
                                        <button id="videoComplete{{ $content->id }}"
                                            onclick="VideoCompleted({{ $content->id }}, {{ $module_id }})"
                                            class="btn btn-primary" style="padding: 8px 15px; font-size: 14px;">
                                            Mark As Completed
                                        </button>
                                    @else
                                        <button class="btn btn-success" style="padding: 8px 15px; font-size: 14px;"
                                            disabled>
                                            Completed
                                        </button>
                                    @endif
                                </div>

                                <!-- Video Player -->
                                <div style="display: flex; justify-content: center; margin-top: 10px;">
                                    <video style="width: 100%; max-width: 600px; border-radius: 8px;" controls>
                                        <source src="{{ asset($content->path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>

                <!-- Audio Section -->
                <h3 style="text-align: center; font-weight: bold; margin-top: 25px; margin-bottom: 15px;">Audios</h3>
                <ul style="list-style: none; padding: 0; font-size: 16px; margin: 0;">
                    @foreach ($contents as $content)
                        @if ($content->type === 'audio')
                            <li style="margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
                                <!-- Audio Title & Button -->
                                <div
                                    style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px;">
                                    <strong style="flex: 1; min-width: 150px;">{{ $content->title }}</strong>

                                    @if ($content->watch_status === 'Pending')
                                        <button id="audioComplete{{ $content->id }}"
                                            onclick="audioCompleted({{ $content->id }}, {{ $module_id }})"
                                            class="btn btn-primary" style="padding: 8px 15px; font-size: 14px;">
                                            Mark As Completed
                                        </button>
                                    @else
                                        <button class="btn btn-success" style="padding: 8px 15px; font-size: 14px;"
                                            disabled>
                                            Completed
                                        </button>
                                    @endif
                                </div>

                                <!-- Audio Player -->
                                <div style="display: flex; justify-content: center; margin-top: 10px;">
                                    <audio style="width: 100%; max-width: 600px; border-radius: 8px;" controls>
                                        <source src="{{ asset($content->path) }}" type="audio/mp3">
                                        Your browser does not support the audio tag.
                                    </audio>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p style="text-align: center; font-weight: bold; color: red;">No audios/videos found</p>
            @endif
        </div>
    </div>
</div>

<script>
    function VideoCompleted(id, module_id) {
        var button = $("#videoComplete" + id);
        button.prop('disabled', true);
        $.ajax({
            url: "{{ route('videoscompleted') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "module_id": module_id
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Video marked as completed');
                } else {
                    toastr.error('Something went wrong');
                }
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                toastr.error('Something went wrong. Please try again.');
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
        });
    }

    function audioCompleted(id, module_id) {
        var button = $("#audioComplete" + id);
        button.prop('disabled', true);
        $.ajax({
            url: "{{ route('videoscompleted') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "module_id": module_id
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Audio marked as completed');
                } else {
                    toastr.error('Something went wrong');
                }
                setTimeout(function() {
                    location.reload();
                }, 2000);
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
