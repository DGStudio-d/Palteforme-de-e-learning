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
        // Only teachers can see their created courses
        $courses = Course::where('teacher_id', Auth::id())
            ->with('teacher')
            ->get();
        return response()->json($courses);
    }

    public function publicCourses()
    {
        $courses = Course::with('teacher')
            ->withCount('students')
            ->get();
        return response()->json($courses);
    }

    public function enrolledCourses()
    {
        $courses = Auth::user()
            ->enrolledCourses()
            ->with('teacher')
            ->get();
        return response()->json($courses);
    }

    public function show(Course $course)
    {
        $course->load(['teacher', 'quizzes']);
        
        // Add enrollment status for the current user
        $course->is_enrolled = $course->students()->where('user_id', Auth::id())->exists();
        
        return response()->json($course);
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
        // Check if user is the teacher of the course
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($validated);

        return response()->json($course);
    }

    public function destroy(Course $course)
    {
        // Check if user is the teacher of the course
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $course->delete();
        return response()->json(null, 204);
    }

    public function enroll(Course $course)
    {
        $user = Auth::user();
        
        // Check if already enrolled
        if ($course->students()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already enrolled'], 400);
        }

        $course->students()->attach($user->id);
        return response()->json(['message' => 'Enrolled successfully']);
    }

    public function unenroll(Course $course)
    {
        $user = Auth::user();
        
        // Check if enrolled
        if (!$course->students()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Not enrolled'], 400);
        }

        $course->students()->detach($user->id);
        return response()->json(['message' => 'Unenrolled successfully']);
    }

    public function students(Course $course)
    {
        // Check if user is the teacher of the course
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $students = $course->students()
            ->withPivot('created_at')
            ->with(['results' => function ($query) use ($course) {
                $query->whereHas('quiz', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                });
            }])
            ->get();

        return response()->json($students);
    }
}
