@extends('include.master')
@section('content')
    <style>
        .option-text {
            font-size: 15px;
            margin-left: 8px;
        }
    </style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 text-center">
                                        <h5><b>Assessment {{ $title->course_title ?? 'N/A' }}</b></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($questions->isNotEmpty())
                            <form id="quizForm" action="{{ route('submit.quiz') }}" method="POST">
                                @csrf
                                @foreach ($questions as $index => $question)
                                    <div class="card card-primary card-outline">
                                        <div class="card-body">
                                            <div class="row justify-content-center">
                                                <div class="col-md-12">
                                                    <div class="mb-4">
                                                        <h5><b>Question {{ $index + 1 }}: {{ $question->question }}</b>
                                                        </h5>
                                                        <ul class="list-unstyled">
                                                            <input name="quiz_id" type="hidden"
                                                                value="{{ $question->quiz_id }}" class="form-control"
                                                                id="quiz_id">
                                                            <li class="mb-2">
                                                                <div class="icheck-primary d-block">
                                                                    <input type="radio"
                                                                        name="answers[{{ $question->id }}]"
                                                                        id="option1_{{ $question->id }}" value="option1"
                                                                        required>
                                                                    <label for="option1_{{ $question->id }}"
                                                                        class="text-dark fw-normal">{{ $question->option1 }}</label>
                                                                </div>
                                                            </li>
                                                            <li class="mb-2">
                                                                <div class="icheck-primary d-block">
                                                                    <input type="radio"
                                                                        name="answers[{{ $question->id }}]"
                                                                        id="option2_{{ $question->id }}" value="option2">
                                                                    <label for="option2_{{ $question->id }}"
                                                                        class="text-dark fw-normal">{{ $question->option2 }}</label>
                                                                </div>
                                                            </li>
                                                            <li class="mb-2">
                                                                <div class="icheck-primary d-block">
                                                                    <input type="radio"
                                                                        name="answers[{{ $question->id }}]"
                                                                        id="option3_{{ $question->id }}" value="option3">
                                                                    <label for="option3_{{ $question->id }}"
                                                                        class="text-dark fw-normal">{{ $question->option3 }}</label>
                                                                </div>
                                                            </li>
                                                            <li class="mb-2">
                                                                <div class="icheck-primary d-block">
                                                                    <input type="radio"
                                                                        name="answers[{{ $question->id }}]"
                                                                        id="option4_{{ $question->id }}" value="option4">
                                                                    <label for="option4_{{ $question->id }}"
                                                                        class="text-dark fw-normal">{{ $question->option4 }}</label>
                                                                </div>
                                                            </li>
                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success px-4 py-2" id="submitBtn">Submit
                                        Quiz</button>
                                </div>
                            </form>
                        @else
                            <p class="text-center">No questions available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#quizForm").submit(function(event) {
                event.preventDefault();
                let submitBtn = $("#submitBtn");
                submitBtn.prop("disabled", true).text("Submitting...");

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            toastr.success("Quiz Submitted Successfully", "Redirecting...");
                            setTimeout(function() {
                                window.location.href = "{{ route('coursemodules') }}";
                            }, 2000);
                        } else {
                            toastr.error(response.message, "Error");
                            submitBtn.prop("disabled", false).text("Submit Quiz");
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message ||
                            "An error occurred. Please try again.", "Error");
                        submitBtn.prop("disabled", false).text("Submit Quiz");
                    }
                });
            });
        });
    </script>
@endsection
