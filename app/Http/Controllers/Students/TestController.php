<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function view($id)
    {
        try {
            $quiz_id = $id;
            $userId = Auth::user()->empid;

            // Increase attempt count
            $userAssessment = DB::table('user_assessment')
                ->where('user_id', $userId)
                ->where('quiz_id', $quiz_id)
                ->first();

            if ($userAssessment) {
                $attempts = $userAssessment->attempts + 1;
                DB::table('user_assessment')
                    ->where('user_id', $userId)
                    ->where('quiz_id', $quiz_id)
                    ->update(['attempts' => $attempts]);
            } else {
                DB::table('user_assessment')->insert([
                    'user_id' => $userId,
                    'quiz_id' => $quiz_id,
                    'status' => 'Attempted',
                    'attempts' => 1,
                    'score' => 0,
                    'result' => 'PENDING',
                    'completed_on' => null
                ]);
            }

            $questions = DB::table('question_master')
                ->where('quiz_id', $quiz_id)
                ->get();

            return view('Students.assessment.question', compact('questions'));
        } catch (Exception $e) {
            return back()->with("error", "Something Went Wrong");
        }
    }

    public function reattempt(Request $request)
    {
        try {
            $quizId = $request->quiz_id;
            $userId = Auth::user()->empid;

            $updated = DB::table('user_assessment')
                ->where('quiz_id', $quizId)
                ->where('user_id', $userId)
                ->update(['status' => 'Waiting']);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Re-attempt request sent. Status updated to Waiting']);
            } else {
                return response()->json(['success' => false, 'message' => 'No matching record found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    public function insert(Request $request)
    {
        try {
            $userId = Auth::user()->empid;
            $quizId = $request->input('quiz_id');
            $userAnswers = $request->input('answers');

            // Fetch correct answers from question_master table
            $correctAnswers = DB::table('question_master')
                ->whereIn('id', array_keys($userAnswers))
                ->pluck('correct_ans', 'id')
                ->toArray();

            $totalQuestions = count($correctAnswers);
            $scorePerQuestion = ($totalQuestions > 0) ? (100 / $totalQuestions) : 0;
            $score = 0;

            // Compare user's answers with correct answers
            foreach ($correctAnswers as $questionId => $correctAnswer) {
                if (isset($userAnswers[$questionId]) && $userAnswers[$questionId] == $correctAnswer) {
                    $score += $scorePerQuestion;
                }
            }

            // Round the score to 2 decimal places
            $score = round($score, 2);

            // Fetch passing criteria from quiz_master table
            $passingScore = DB::table('quiz_master')
                ->where('id', $quizId)
                ->value('passing_marks');

            // Ensure passingScore is not null; default to 50% if missing
            $passingScore = $passingScore ?? 50;

            $result = ($score >= $passingScore) ? 'PASS' : 'FAIL';

            // Update the assessment record with the score and result
            DB::table('user_assessment')
                ->where('user_id', $userId)
                ->where('quiz_id', $quizId)
                ->update([
                    'status' => 'Attempted',
                    'score' => $score,
                    'result' => $result,
                    'completed_on' => now()
                ]);

            // Check if all quizzes in the same module are passed before updating approval_status
            if ($result === 'PASS') {
                $this->checkAndUpdateApprovalStatus($userId, $quizId);
            }

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => "Something went wrong", 'success' => false]);
        }
    }

    private function checkAndUpdateApprovalStatus($userId, $quizId)
    {
        // Get the module_id of the given quiz
        $moduleId = DB::table('quiz_master')
            ->where('id', $quizId)
            ->value('module_id');

        if (!$moduleId) {
            return; // No module found, exit function
        }

        // Get all quiz IDs under the same module
        $quizIds = DB::table('quiz_master')
            ->where('module_id', $moduleId)
            ->pluck('id')
            ->toArray();

        // Count total quizzes in this module
        $totalQuizzes = count($quizIds);

        // Count the number of quizzes the user has attempted and passed
        $passedQuizzes = DB::table('user_assessment')
            ->where('user_id', $userId)
            ->whereIn('quiz_id', $quizIds)
            ->where('status', 'Attempted')
            ->where('result', 'PASS')
            ->count();

        // If all quizzes in the module are passed, update approval_status, course_status, and module_status
        if ($totalQuizzes > 0 && $totalQuizzes === $passedQuizzes) {
            DB::table('user_progress')
                ->where('empid', $userId)
                ->where('module_id', $moduleId)
                ->update([
                    'approval_status' => 'Approved',
                    'course_status' => 'Completed',
                    'module_status' => 'Completed',
                    'approved_by' => 'Server',
                    'completed_on' => now()
                ]);
        }
    }
}