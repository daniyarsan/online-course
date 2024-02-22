import React, {useContext, useEffect, useState} from 'react'
import {useParams} from "react-router-dom";
import Lesson from "../../components/Lesson/Lesson.jsx";
import LessonsMenu from "../../components/UI/LessonsMenu/LessonsMenu.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {COURSE_DOMAIN, LESSON_DOMAIN} from "../../api/_const.js";
import UserCourseModel from "../../models/UserCourse.js";
import {toast} from "react-toastify";
import LessonModel from "../../models/Lesson.js";

function LessonPage() {
  const {id} = useParams()
  const {requester} = useContext(RequestContext)
  const {setLoader} = useContext(AuthContext)
  const [courseId, setCourseId] = useState()


  useEffect(() => {
    setLoader(true)
    requester.get(`${LESSON_DOMAIN}/${id}`).then(({data}) => {
      setCourseId(data.courseId)
    }).catch((err) => {
      toast.error(err.message, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      });
    }).finally(() => {
      setLoader(false)
    })
  }, [])


  return (
      <>
        <section className="mt-11 course-container">
          <div className="pt-3 container-fluid">
            <div className="tab-content content">
              <Lesson id={id}/>
            </div>
          </div>
        </section>

        <section className="mt-11 card course-sidebar " id="courseAccordion">
          {courseId && <LessonsMenu courseId={courseId} lessonId={+id} />}
        </section>
      </>
  )
}

export default LessonPage

