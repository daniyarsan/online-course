import React, {useContext, useEffect, useState} from 'react';
import LessonsChapterRow from "./LessonsChapterRow.jsx";
import {ceil, floor} from "loadsh/math.js";
import {COURSE_DOMAIN} from "../../../api/_const.js";
import UserCourseModel from "../../../models/UserCourse.js";
import {toast} from "react-toastify";
import {RequestContext} from "../../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../../contexts/AuthProvider.jsx";

const LessonsMenu = ({courseId, lessonId}) => {
  const {requester} = useContext(RequestContext)
  const {setLoader} = useContext(AuthContext)
  const [course, setCourse] = useState()
  const [passedLessonIds, setPassedLessonIds] = useState([])
  const [currentChapterId, setCurrentChapterId] = useState()
  const [progress, setProgress] = useState(0)


  useEffect(() => {
    requester.get(`${COURSE_DOMAIN}/${courseId}`).then(({data}) => {
      const {course, lessonResults, currentLesson} = new UserCourseModel(data)
      const passedLessonIds = lessonResults.map(({lesson}) => lesson.id)
      setCourse(course)
      setPassedLessonIds(passedLessonIds)
      setProgress(ceil((passedLessonIds.length * 100) / course.lessons.length))
      setPassedLessonIds([...passedLessonIds, currentLesson.id])


    }).catch((err) => {
      toast.error(err.message, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      })
    }).finally(() => {
      setLoader(false)
    })

  }, []);


  return (
      <div className="card" id="courseAccordion">
        <br/>
        <ul className="list-group list-group-flush">
          <li className="list-group-item">
            <div>
              <div className="progress" style={{height: '6px'}}>
                <div className="progress-bar bg-success" role="progressbar" style={{width: `${progress}%`}}></div>
              </div>
              <small>{progress}% выполнено</small>
            </div>
          </li>
        </ul>

        <div>
          <ul className="list-group list-group-flush">
            {course && course.chapters.map(chapter => <LessonsChapterRow
                key={chapter.id}
                chapter={chapter}
                currentChapterId={chapter.lessons.map(lesson => lesson.id).includes(lessonId) && chapter.id}
                passedLessonIds={passedLessonIds}
                currentLessonId={lessonId} />)}
          </ul>
        </div>
      </div>
  );
};

export default LessonsMenu;