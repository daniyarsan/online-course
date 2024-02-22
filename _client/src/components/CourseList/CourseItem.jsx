import React, {useContext, useEffect, useState} from 'react';
import {COURSE_DOMAIN} from "../../api/_const.js";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {useNavigate} from "react-router-dom";
import {toast} from "react-toastify";
import UserCourse from "../../models/UserCourse.js";

const CourseItem = ({course, userCourses}) => {
  const navigate = useNavigate()
  const {setLoader} = useContext(AuthContext)
  const {requester} = useContext(RequestContext)
  const [currentUserCourse, setCurrentUserCourse] = useState()

  useEffect(() => {
    setCurrentUserCourse(userCourses.find(userCourse => userCourse.course.id === course.id))
  }, []);

  function startCourse() {
    setLoader(true)
    requester.post(`${COURSE_DOMAIN}/start/${course.id}`).then(({data}) => {
      const userCourse = new UserCourse(data)
      navigate(`/course/${userCourse.course.id}`)
    }).catch((err) => {
      toast.error(err.message, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      });
    }).finally(() => {
      setLoader(false)
    })
  }


  if (currentUserCourse) {
    return (
        <tr>
          <td>
            <div className="d-flex align-items-center">
              {/*<div><a href="#"><img src="../assets/images/course/course-wordpress.jpg" alt="course" className="rounded img-4by3-lg"/></a></div>*/}

              <div className="ms-3">
                <h4 className="mb-1 h5">{course.title}</h4>
                <ul className="list-inline fs-6 mb-0">
                  <li className="list-inline-item">
                    {course.courseCategory}
                  </li>
                </ul>
              </div>
            </div>
          </td>
          <td>{course.lessons.length}</td>
          <td>{currentUserCourse.course.lessons.length}</td>
          <td>
            {(currentUserCourse.lessonResults.length === course.lessons.length)
                ? <button onClick={() => {navigate(`/course/${course.id}`)}} className="btn btn-primary btn-sm">Курс Пройден</button>
                : <button onClick={() => {navigate(`/lesson/${currentUserCourse.currentLesson.id}`)}} className="btn btn-primary btn-sm">Продолжить...</button>
            }

          </td>
        </tr>
    )
  }

  return (
      <tr>
        <td>
          <div className="d-flex align-items-center">
            <div>
              <a href="#">{/*<img src="../assets/images/course/course-wordpress.jpg" alt="course" className="rounded img-4by3-lg"/>*/}</a>
            </div>
            <div className="ms-3">
              <h4 className="mb-1 h5">{course.title}</h4>
              <ul className="list-inline fs-6 mb-0">
                <li className="list-inline-item">
                  {course.courseCategory}
                </li>
              </ul>
            </div>
          </div>
        </td>
        <td>{course.lessons.length}</td>
        <td></td>
        {/*<td>*/}
        {/*  <span className="badge bg-success">Live</span>*/}
        {/*</td>*/}
        <td>
          <button onClick={() => startCourse()} className="btn btn-primary btn-sm">Приступить</button>
        </td>
      </tr>
  )
}


export default CourseItem;