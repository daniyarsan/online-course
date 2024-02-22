import React, {useContext, useEffect, useState} from 'react'
import {useParams} from "react-router-dom";
import Course from "../../components/Course/Course.jsx";
import LessonsMenu from "../../components/UI/LessonsMenu/LessonsMenu.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {COURSE_DOMAIN} from "../../api/_const.js";
import UserCourseModel from "../../models/UserCourse.js";
import {toast} from "react-toastify";

function CoursePage() {
  const {id} = useParams()
  const {requester} = useContext(RequestContext)
  const {setLoader} = useContext(AuthContext)
  const [userCourse, setUserCourse] = useState()

  useEffect(() => {
    setLoader(true)
    requester.get(`${COURSE_DOMAIN}/${id}`).then(({data}) => {
      setUserCourse(new UserCourseModel(data))
    }).catch((err) => {
      toast.error(err.message, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      });
    }).finally(() => {
      setLoader(false)
    })
  }, [])

  console.log(userCourse?.currentLesson?.id)


  return userCourse && (
      <section className="p-lg-5 py-7">
        <div className="container">
          <div className="row">
            <div className="col-xl-8 col-lg-12 col-md-12 col-12 mb-4 mb-xl-0">
              <Course course={userCourse?.course} />
            </div>
            <div className="col-xl-4 col-lg-12 col-md-12 col-12">
              <LessonsMenu courseId={id} lessonId={userCourse?.currentLesson?.id} />
            </div>
          </div>
        </div>
      </section>
  )
}

export default CoursePage

