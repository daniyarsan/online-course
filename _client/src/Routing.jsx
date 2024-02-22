import {Route, Routes, Navigate} from "react-router-dom";

import React, {useContext} from "react";
import {RegistrationPage} from "./pages/Auth/RegistrationPage.jsx";
import {LoginPage} from "./pages/Auth/LoginPage.jsx";
import {AuthContext} from "./contexts/AuthProvider.jsx";
import DashboardLayout from "./layouts/Dashboard/DashboardLayout.jsx";
import ResultsPage from "./pages/Results/ResultsPage.jsx";
import ContactPage from "./pages/Contact/ContactPage.jsx";
import MainLayout from "./layouts/Main/MainLayout.jsx";
import QuizPage from "./pages/Quiz/QuizPage.jsx";
import AuthLayout from "./layouts/Auth/AuthLayout.jsx";
import AnonContactPage from "./pages/Contact/AnonContactPage.jsx";
import StatisticsPage from "./pages/Statistics/StatisticsPage.jsx";
import CourseListPage from "./pages/CourseList/CourseListPage.jsx";
import {QuizListPage} from "./pages/QuizList/QuizListPage.jsx";
import CoursePage from "./pages/Course/CoursePage.jsx";
import ClassroomLayout from "./layouts/Classroom/ClassroomLayout.jsx";
import LessonPage from "./pages/Lesson/LessonPage.jsx";


function Routing() {
  const { currentUser } = useContext(AuthContext);

  if (currentUser.isAuthorized) {
    return (
        <Routes>
          <Route element={<DashboardLayout/>}>
            <Route path="/statistics" element={<StatisticsPage/>}/>
            <Route path="/results" element={<ResultsPage/>}/>
            <Route path="/contact" element={<ContactPage/>}/>
          </Route>

          <Route element={<MainLayout/>}>
            <Route path="/" element={<QuizListPage/>}/>
            <Route path="/quiz" element={<QuizListPage/>}/>
            <Route path="/quiz/:id" element={<QuizPage/>}/>
            <Route path="/course" element={<CourseListPage/>}/>
            <Route path="/course/:id" element={<CoursePage/>}/>
            <Route path="*" element={<Navigate replace to="/"/>}/>
          </Route>

          <Route element={<ClassroomLayout/>}>
            <Route path="/lesson/:id" element={<LessonPage />}/>
          </Route>
        </Routes>
    )
  }

    return (
        <Routes>
          <Route element={<AuthLayout/>}>
            <Route path="/" element={<Navigate replace to="/login"/>}/>
            <Route path="/login" element={<LoginPage/>}/>
            <Route path="/registration" element={<RegistrationPage/>}/>
            <Route path="/contact" element={<AnonContactPage/>} />
            <Route path="*" element={<Navigate replace to="/"/>}/>
          </Route>
        </Routes>
    )
}

export default Routing
