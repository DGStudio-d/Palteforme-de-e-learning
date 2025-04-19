import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

interface Quiz {
  id: number;
  title: string;
  course: {
    id: number;
    title: string;
  };
  duration_minutes: number;
  is_active: boolean;
}

export default function QuizList() {
  const [quizzes, setQuizzes] = useState<Quiz[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchQuizzes();
  }, []);

  const fetchQuizzes = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/quizzes');
      const data = await response.json();
      setQuizzes(data);
    } catch (error) {
      console.error('Error fetching quizzes:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {quizzes.map((quiz) => (
        <div key={quiz.id} className="bg-white rounded-lg shadow-md p-6">
          <div className="flex items-center justify-between">
            <div>
              <h3 className="text-xl font-semibold text-gray-900">{quiz.title}</h3>
              <p className="text-gray-600 mt-1">Course: {quiz.course.title}</p>
              <p className="text-sm text-gray-500 mt-2">
                Duration: {quiz.duration_minutes} minutes
              </p>
            </div>
            <div className="flex items-center space-x-4">
              <span
                className={`px-3 py-1 rounded-full text-sm ${
                  quiz.is_active
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800'
                }`}
              >
                {quiz.is_active ? 'Active' : 'Inactive'}
              </span>
              <Link
                to={`/quizzes/${quiz.id}`}
                className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
              >
                Start Quiz
              </Link>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
} 