import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Layout from './components/layout/Layout';
import CourseList from './components/course/CourseList';
import QuizList from './components/quiz/QuizList';
import './App.css'

function App() {
  return (
    <Router>
      <Layout>
        <Routes>
          <Route path="/" element={<CourseList />} />
          <Route path="/courses" element={<CourseList />} />
          <Route path="/quizzes" element={<QuizList />} />
        </Routes>
      </Layout>
    </Router>
  );
}

export default App;
