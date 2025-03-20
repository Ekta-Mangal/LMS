<form action="{{ route('manageContent.updatequiz') }}" method="post" id="editContent" enctype="multipart/form-data">
    @csrf
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="row">
                <input name="module_id" type="hidden" value="{{ $quizData[0]['module_id'] }}" class="form-control"
                    id="module_id">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="quiz_title">Quiz Title</label>
                        <input type="text" name="quiz_title" class="form-control"
                            value="{{ $quizData[0]['quiz_title'] }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="quiz_description">Quiz Description</label>
                        <textarea name="quiz_description" class="form-control">{{ $quizData[0]['quiz_description'] }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="passing_marks">Passing Marks</label>
                        <input type="number" name="passing_marks" class="form-control"
                            value="{{ $quizData[0]['passing_marks'] }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="allow_attempts">Allowed Attempts</label>
                        <input type="number" name="allow_attempts" class="form-control"
                            value="{{ $quizData[0]['allow_attempts'] }}">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <label class="col-12 font-weight-bold text-lg">Quiz Questions</label>
                @foreach ($quizData[0]['questions'] as $question)
                    <div class="col-md-12">
                        <div class="mb-4">
                            <h5><b>Question {{ $loop->iteration }}: {{ $question['question'] }}</b></h5>
                            <input name="quiz_id" type="hidden" value="{{ $quizData[0]['quiz_id'] }}"
                                class="form-control" id="quiz_id">
                            <ul class="list-unstyled">
                                @foreach ([1, 2, 3, 4] as $num)
                                    <li class="mb-2">
                                        <div class="icheck-primary d-block">
                                            <input type="radio" name="answers[{{ $question['question_id'] }}]"
                                                id="option{{ $num }}_{{ $question['question_id'] }}"
                                                value="option{{ $num }}"
                                                {{ $question['correct_ans'] == "option$num" ? 'checked' : '' }}
                                                required>
                                            <label for="option{{ $num }}_{{ $question['question_id'] }}"
                                                class="text-dark fw-normal">
                                                {{ $question["option$num"] }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>
