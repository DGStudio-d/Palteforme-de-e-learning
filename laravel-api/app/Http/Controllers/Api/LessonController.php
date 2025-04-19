<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        $lessons = $course->lessons()->ordered()->get();
        return response()->json($lessons);
    }

    public function show(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            return response()->json(['message' => 'Lesson not found in this course'], 404);
        }

        return response()->json($lesson);
    }

    public function store(Request $request, Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'order' => 'integer|min:0',
            'is_published' => 'boolean',
        ]);

        $lesson = $course->lessons()->create($validated);

        return response()->json($lesson, 201);
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($lesson->course_id !== $course->id) {
            return response()->json(['message' => 'Lesson not found in this course'], 404);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'content' => 'string',
            'order' => 'integer|min:0',
            'is_published' => 'boolean',
        ]);

        $lesson->update($validated);

        return response()->json($lesson);
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($lesson->course_id !== $course->id) {
            return response()->json(['message' => 'Lesson not found in this course'], 404);
        }

        $lesson->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request, Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['lessons'] as $lessonData) {
            $lesson = Lesson::find($lessonData['id']);
            if ($lesson->course_id === $course->id) {
                $lesson->update(['order' => $lessonData['order']]);
            }
        }

        return response()->json(['message' => 'Lessons reordered successfully']);
    }
} 