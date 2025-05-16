<div class="card card-primary card-outline" style="border: 1px solid #ccc; padding: 10px;">
    <div class="card-body">
        <!-- Course Title -->
        <div
            style="text-align: center; font-size: 25px; font-weight: bold; padding: 20px; border: 1px solid #ccc; margin-bottom: 20px;">
            {{ $module->course_title }}
        </div>

        <!-- Module Details Button -->
        <div style="text-align: center; margin-bottom: 22px;">
            <button class="btn" style="background-color: #f8f0a1; border: none; padding: 8px 20px;">
                Module Details
            </button>
        </div>

        <!-- Modules Section -->
        <div style="border: 1px solid #ccc; padding: 15px;">
            <h5><b>Module - 3 : Assessment</b></h5>
            <br>
            @foreach ($moduleDetails as $test)
                <div style="border: 1px solid #ccc; padding: 15px; margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h5><b>Title : {{ $test->quiz_title }}</b></h5>
                            <p style="font-size: 16px; color: #555; margin: 0;">{{ $test->quiz_description }}</p>
                            <p style="margin: 5px 0;"><b>Passing Marks:</b> {{ $test->passing_marks }}</p>
                        </div>

                        @if ($test->assessment_status === 'Attempted')
                            @if ($test->assessment_result === 'PASS')
                                <button type="button" class="btn bg-gradient-success text-white"
                                    style="padding: 10px 20px; font-size: 16px;" disabled>
                                    Passed
                                </button>
                            @elseif ($test->assessment_result === 'FAIL')
                                <div style="display: flex; gap: 5px; align-items: center;">
                                    <button type="button" class="btn bg-gradient-danger text-white"
                                        style="padding: 10px 20px; font-size: 16px;" disabled>
                                        Failed
                                    </button>

                                    @if ($test->allow_attempts >= $test->assessment_attempts)
                                        <button type="button" class="btn bg-gradient-primary text-white"
                                            style="padding: 10px 20px; font-size: 16px;"
                                            id="reattemptButton_{{ $test->id }}"
                                            onclick="reattempt({{ $test->id }})">
                                            Re-Attempt
                                        </button>
                                    @else
                                        <button type="button" class="btn bg-gradient-danger text-white"
                                            style="padding: 10px 20px; font-size: 16px;"
                                            onclick="restart({{ $test->module_id }})">
                                            Restart Course
                                        </button>
                                    @endif
                                </div>
                            @endif
                        @elseif ($test->assessment_status === 'Waiting')
                            <button type="button" class="btn bg-gradient-danger text-white"
                                style="padding: 10px 20px; font-size: 16px;" disabled>
                                Wait for 24Hrs to Re-Attempt
                            </button>
                        @elseif ($test->assessment_status === 'Pending')
                            @if (is_null($test->assessment_result))
                                <a href="{{ route('start.test', ['id' => $test->id]) }}" class="btn btn-primary"
                                    style="padding: 10px 20px; font-size: 16px;">
                                    Start Test
                                </a>
                            @elseif ($test->assessment_result === 'FAIL')
                                <a href="{{ route('start.test', ['id' => $test->id]) }}" class="btn btn-primary"
                                    style="padding: 10px 20px; font-size: 16px;">
                                    Re-Attempt Test
                                </a>
                            @endif
                        @else
                            <button type="button" class="btn bg-gradient-danger text-white"
                                style="padding: 10px 20px; font-size: 16px;" disabled>
                                Contact Admin
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

<script>
    function reattempt(id) {
        var button = document.getElementById("reattemptButton_" + id);
        button.disabled = true; // Disable button after click
        button.innerHTML = "Processing...";

        $.ajax({
            url: "{{ route('assessment.reattempt') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "quiz_id": id
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    toastr.error(response.message);
                    button.disabled = false; // Re-enable button if error
                    button.innerHTML = "Re-Attempt";
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                button.disabled = false; // Re-enable button if error
                button.innerHTML = "Re-Attempt";
            }
        });
    }
</script>

<script>
    function restart(id) {
        $.ajax({
            type: "get",
            url: "{{ route('restart') }}",
            data: {
                "id": id
            },
            success: function(data) {
                $("#restartModal").modal('show');
                $('#restartBody').html(data.html);
            }
        });
    };
</script>

<div id="restartModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="card-body" id="restartBody">
            </div>
        </div>
    </div>
</div>
