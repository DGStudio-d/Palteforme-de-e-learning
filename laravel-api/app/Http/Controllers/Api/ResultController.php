namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $result = Result::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $validated['score'],
            'completed_at' => now(),
        ]);

        return response()->json($result, 201);
    }

    public function getUserResults()
    {
        $results = Result::where('user_id', Auth::id())
            ->with(['quiz.course'])
            ->orderBy('completed_at', 'desc')
            ->get();

        return response()->json($results);
    }

    public function getQuizResults(Quiz $quiz)
    {
        // Check if user is the teacher of the course
        if ($quiz->course->teacher_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $results = $quiz->results()
            ->with('user:id,name')
            ->orderBy('score', 'desc')
            ->get();

        return response()->json($results);
    }

    public function getHighScores(Quiz $quiz)
    {
        $results = $quiz->results()
            ->with('user:id,name')
            ->where('score', '>=', 80)
            ->orderBy('score', 'desc')
            ->get();

        return response()->json($results);
    }

    public function getAverageScore(Quiz $quiz)
    {
        $average = $quiz->results()->avg('score');
        return response()->json(['average' => $average]);
    }
} 