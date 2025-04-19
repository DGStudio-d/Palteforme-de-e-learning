<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('course')->get();
        return response()->json($quizzes);
    }

    public function show(Quiz $quiz)
    {
        return response()->json($quiz->load('course'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        
        // Check if user is the teacher of the course
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quiz = Quiz::create($validated);

        return response()->json($quiz, 201);
    }

    public function update(Request $request, Quiz $quiz)
    {
        // Check if user is the teacher of the course
        if ($quiz->course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $quiz->update($validated);

        return response()->json($quiz);
    }

    public function destroy(Quiz $quiz)
    {
        // Check if user is the teacher of the course
        if ($quiz->course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quiz->delete();
        return response()->json(null, 204);
    }

    public function getStudentResults(Quiz $quiz)
    {
        $results = $quiz->results()
            ->with('user:id,name')
            ->orderBy('score', 'desc')
            ->get();

        return response()->json($results);
    }
}
