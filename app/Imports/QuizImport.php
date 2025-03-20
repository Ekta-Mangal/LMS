<?php

namespace App\Imports;

use App\Models\Questions;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuizImport implements ToModel, WithHeadingRow
{
    protected $module_id;
    protected $quiz_id;

    public function __construct($module_id, $quiz_id)
    {
        $this->module_id = $module_id;
        $this->quiz_id = $quiz_id;
    }

    public function model(array $row)
    {
        return new Questions([
            'module_id' => $this->module_id,
            'quiz_id' => $this->quiz_id,
            'question' => $row['question'],
            'option1' => $row['option1'],
            'option2' => $row['option2'],
            'option3' => $row['option3'],
            'option4' => $row['option4'],
            'correct_ans' => $row['correct_ans'],
            'created_by' => Auth::user()->name,
        ]);
    }
}