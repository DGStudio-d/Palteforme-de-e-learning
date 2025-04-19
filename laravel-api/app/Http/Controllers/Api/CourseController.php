<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher')->get();
        return response()->json($courses);
    }

    public function show(Course $course)
    {
        return response()->json($course->load(['teacher', 'quizzes']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course = Course::create([
            ...$validated,
            'teacher_id' => Auth::id(),
        ]);

        return response()->json($course, 201);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);

        return response()->json($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(null, 204);
    }

    public function enroll(Course $course)
    {
        $user = Auth::user();
        $course->students()->attach($user->id);
        return response()->json(['message' => 'Enrolled successfully']);
    }

    public function unenroll(Course $course)
    {
        $user = Auth::user();
        $course->students()->detach($user->id);
        return response()->json(['message' => 'Unenrolled successfully']);
    }
}
