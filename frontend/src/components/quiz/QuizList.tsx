import { useEffect, useState } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { quizApi } from '../../services/api';
import { Link } from 'react-router-dom';

interface Quiz {
  id: number;
  title: string;
  course: {
    title: string;
  };
  duration_minutes: number;
  is_active: boolean;
  questions_count?: number;
}

export default function QuizList() {
  const { user } = useAuth();
  const [quizzes, setQuizzes] = useState<Quiz[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchQuizzes = async () => {
      try {
        setLoading(true);
        const data = await quizApi.getQuizzes();
        setQuizzes(data);
      } catch (err) {
        setError('Failed to fetch quizzes');
      } finally {
        setLoading(false);
      }
    };

    fetchQuizzes();
  }, []);

  const handleCreateQuiz = async () => {
    // TODO: Implement quiz creation modal
  };

  if (loading) {
    return <div>Loading quizzes...</div>;
  }

  if (error) {
    return <div className="text-red-500">{error}</div>;
  }

  return (
    <div className="py-6">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-3xl font-bold text-gray-900">
            {user?.role === 'student' ? 'Available Quizzes' : 'Quizzes'}
          </h1>
          {user?.role === 'teacher' && (
            <button
              className="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
              onClick={handleCreateQuiz}
            >
              Create Quiz
            </button>
          )}
        </div>

        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {quizzes.map((quiz) => (
            <div key={quiz.id} className="bg-white shadow rounded-lg overflow-hidden">
              <div className="p-6">
                <h2 className="text-xl font-semibold text-gray-900 mb-2">
                  {quiz.title}
                </h2>
                <p className="text-gray-600 mb-2">
                  Course: {quiz.course.title}
                </p>
                <p className="text-sm text-gray-500 mb-2">
                  Duration: {quiz.duration_minutes} minutes
                </p>
                {quiz.questions_count !== undefined && (
                  <p className="text-sm text-gray-500 mb-4">
                    Questions: {quiz.questions_count}
                  </p>
                )}
                <div className="flex justify-between items-center">
                  {user?.role === 'student' ? (
                    <Link
                      to={`/quizzes/${quiz.id}/take`}
                      className="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                    >
                      Take Quiz
                    </Link>
                  ) : (
                    <Link
                      to={`/quizzes/${quiz.id}/results`}
                      className="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                    >
                      View Results
                    </Link>
                  )}
                  <span
                    className={`px-2 py-1 text-xs font-semibold rounded-full ${
                      quiz.is_active
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800'
                    }`}
                  >
                    {quiz.is_active ? 'Active' : 'Inactive'}
                  </span>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
} 