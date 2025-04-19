import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add token to requests if it exists
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export interface LoginData {
  email: string;
  password: string;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  role: 'student' | 'teacher' | 'admin';
}

export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
}

export const authApi = {
  login: async (data: LoginData) => {
    const response = await api.post('/login', data);
    return response.data;
  },
  register: async (data: RegisterData) => {
    const response = await api.post('/register', data);
    return response.data;
  },
  logout: async () => {
    const response = await api.post('/logout');
    return response.data;
  },
  getUser: async () => {
    const response = await api.get('/user');
    return response.data;
  },
};

export const courseApi = {
  getPublicCourses: async () => {
    const response = await api.get('/courses/public');
    return response.data;
  },
  getEnrolledCourses: async () => {
    const response = await api.get('/courses/enrolled');
    return response.data;
  },
  getTeacherCourses: async () => {
    const response = await api.get('/courses');
    return response.data;
  },
  getCourse: async (id: number) => {
    const response = await api.get(`/courses/${id}`);
    return response.data;
  },
  createCourse: async (data: any) => {
    const response = await api.post('/courses', data);
    return response.data;
  },
  updateCourse: async (id: number, data: any) => {
    const response = await api.put(`/courses/${id}`, data);
    return response.data;
  },
  deleteCourse: async (id: number) => {
    const response = await api.delete(`/courses/${id}`);
    return response.data;
  },
  enroll: async (courseId: number) => {
    const response = await api.post(`/courses/${courseId}/enroll`);
    return response.data;
  },
  unenroll: async (courseId: number) => {
    const response = await api.post(`/courses/${courseId}/unenroll`);
    return response.data;
  },
};

export const quizApi = {
  getQuizzes: async () => {
    const response = await api.get('/quizzes');
    return response.data;
  },
  getQuiz: async (id: number) => {
    const response = await api.get(`/quizzes/${id}`);
    return response.data;
  },
  createQuiz: async (data: any) => {
    const response = await api.post('/quizzes', data);
    return response.data;
  },
  submitQuiz: async (quizId: number, data: any) => {
    const response = await api.post(`/quizzes/${quizId}/submit`, data);
    return response.data;
  },
  getResults: async (quizId: number) => {
    const response = await api.get(`/quizzes/${quizId}/results`);
    return response.data;
  },
};

export default api; 