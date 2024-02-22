import React, {useContext, useEffect, useState} from "react";
import {BASE_URL, LESSON_DOMAIN} from "../../api/_const.js";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import LessonModel from '../../models/Lesson.js'
import EmbedYoutube from "../UI/EmbedYoutube/EmbedYoutube.jsx";
import slugsService from "loadsh";
import EmbedPdf from "../UI/EmbedPdf/EmbedPdf.jsx";
import {toast} from "react-toastify";
import {useNavigate} from "react-router-dom";

const Lesson = ({id}) => {
  const {requester} = useContext(RequestContext)
  const {setLoader} = useContext(AuthContext)
  const [lessonData, setLessonData] = useState({})

  function completeLesson() {
    setLoader(true)
    requester.post(`${LESSON_DOMAIN}/${lessonData.id}/complete`).then(({data}) => {
      const {nextLesson, userCourse} = data
      if (userCourse.course.lessons.length != userCourse.lessonResults.length) {
        window.location.href = `/lesson/${nextLesson.id}`
        return
      } else {
        window.location.href = `/`
      }
    }).catch((err) => {
      toast.error(err.message, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      })
    }).finally(() => {
      setLoader(false)
    })
  }

  useEffect(() => {
    setLoader(true)
    requester.get(`${LESSON_DOMAIN}/${id}`).then(({data}) => {
      setLessonData(new LessonModel(data))
    }).catch((err) => {
      toast.error(err.message, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      })
    }).finally(() => {
      setLoader(false)
    })
  }, [])


  return (
      <>
        {lessonData.media && lessonData.media.map(media => {
          return (
              <div key={slugsService.kebabCase(media.url)} className="row mt-5">
                <div className="col-12">
                  <EmbedYoutube url={media.url}/>
                </div>
              </div>
          )
        })}

        {lessonData.mediaFile && lessonData.mediaFile.map((mediaFile, index) => {
          return (
              <div key={slugsService.kebabCase(mediaFile.uploadsDirPath)} className="row mt-5">
                <div className="col-12">
                  <EmbedPdf url={`${BASE_URL}${mediaFile.uploadsDirPath}`}/>
                </div>
              </div>
          )
        })}

        <div className="row mt-5">
          <div className="col-12" dangerouslySetInnerHTML={{__html: lessonData.content}}></div>
        </div>
        <div className="col-md-12 col-12 text-center mb-5">
          <div>
            <button className="btn btn-light-dark mb-3 mb-lg-0 me-4">Назад</button>
            <button onClick={() => {
              completeLesson()
            }} className="btn btn-primary mb-3 mb-lg-0 me-4">Завершить урок
            </button>
          </div>
        </div>
      </>
  )
}

export default Lesson