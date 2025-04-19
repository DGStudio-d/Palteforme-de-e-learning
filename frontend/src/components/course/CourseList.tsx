import { useEffect, useState } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { courseApi } from '../../services/api';

interface Course {
  id: number;
  title: string;
  description: string;
  teacher: {
    name: string;
  };
  is_enrolled?: boolean;
  students_count?: number;
}

export default function CourseList() {
  const { user } = useAuth();
  const [courses, setCourses] = useState<Course[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchCourses = async () => {
      try {
        setLoading(true);
        let data;
        if (user?.role === 'student') {
          data = await courseApi.getEnrolledCourses();
        } else if (user?.role === 'teacher') {
          data = await courseApi.getTeacherCourses();
        } else {
          data = await courseApi.getPublicCourses();
        }
        setCourses(data);
      } catch (err) {
        setError('Failed to fetch courses');
      } finally {
        setLoading(false);
      }
    };

    fetchCourses();
  }, [user?.role]);

  const handleEnroll = async (courseId: number) => {
    try {
      await courseApi.enroll(courseId);
      setCourses(courses.map(course => 
        course.id === courseId ? { ...course, is_enrolled: true } : course
      ));
    } catch (err) {
      setError('Failed to enroll in course');
    }
  };

  const handleUnenroll = async (courseId: number) => {
    try {
      await courseApi.unenroll(courseId);
      setCourses(courses.map(course => 
        course.id === courseId ? { ...course, is_enrolled: false } : course
      ));
    } catch (err) {
      setError('Failed to unenroll from course');
    }
  };

  if (loading) {
    return <div>Loading courses...</div>;
  }

  if (error) {
    return <div className="text-red-500">{error}</div>;
  }

  return (
    <div className="py-6">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-3xl font-bold text-gray-900">
            {user?.role === 'student' ? 'My Courses' : 'Courses'}
          </h1>
          {user?.role === 'teacher' && (
            <button
              className="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
              onClick={() => {/* TODO: Implement create course modal */}}
            >
              Create Course
            </button>
          )}
        </div>

        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {courses.map((course) => (
            <div key={course.id} className="bg-white shadow rounded-lg overflow-hidden">
              <div className="p-6">
                <h2 className="text-xl font-semibold text-gray-900 mb-2">
                  {course.title}
                </h2>
                <p className="text-gray-600 mb-4">{course.description}</p>
                <p className="text-sm text-gray-500 mb-4">
                  Teacher: {course.teacher.name}
                </p>
                {course.students_count !== undefined && (
                  <p className="text-sm text-gray-500 mb-4">
                    Students: {course.students_count}
                  </p>
                )}
                {user?.role === 'student' && (
                  <button
                    onClick={() => 
                      course.is_enrolled 
                        ? handleUnenroll(course.id)
                        : handleEnroll(course.id)
                    }
                    className={`w-full px-4 py-2 rounded-md ${
                      course.is_enrolled
                        ? 'bg-red-600 hover:bg-red-700'
                        : 'bg-indigo-600 hover:bg-indigo-700'
                    } text-white`}
                  >
                    {course.is_enrolled ? 'Unenroll' : 'Enroll'}
                  </button>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
} 