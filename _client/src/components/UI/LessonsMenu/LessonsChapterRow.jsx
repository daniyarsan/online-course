import React, {useEffect, useState} from 'react';
import slugsService from 'loadsh'
import {getNoun} from "../../../service/Helper.js";

const LessonsChapterRow = ({chapter, currentChapterId, currentLessonId, passedLessonIds}) => {

  const [open, setOpen] = useState(chapter.id === currentChapterId)

  const LessonRow = ({lesson, current = false, passed = false}) => {
    if (current) {
      return (
          <li className="list-group-item list-group-item-action active">
            <a href={`/lesson/${lesson.id}`} className="d-flex justify-content-between align-items-center text-white text-decoration-none">
              <div className="text-truncate">
                <span className="icon-shape bg-light text-primary icon-sm rounded-circle me-2">
                  <i className="mdi mdi-play fs-4"></i>
                </span>
                <span>{lesson.title}</span>
              </div>
              {/*<div className="text-truncate">*/}
              {/*  <span>1m 7s</span>*/}
              {/*</div>*/}
            </a>
          </li>
      )
    }

    if (passed) {
      return (
          <li className="list-group-item">
            <a href={`/lesson/${lesson.id}`} className="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
              <div className="text-truncate">
                <span className="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i className="mdi mdi-play fs-4"></i></span>
                {lesson.title}
              </div>
              {/*<div className="text-truncate">*/}
              {/*  <span>1m 7s</span>*/}
              {/*</div>*/}
            </a>
          </li>
      )
    }

    return (
        <li className="list-group-item">
          <a href='#' className="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
            <div className="text-truncate">
              <span className="icon-shape bg-light text-muted icon-sm rounded-circle me-2"><i className="fe fe-lock fs-4"></i></span>
              {lesson.title}
            </div>
          </a>
        </li>
    )
  }

  return (
      <li className="list-group-item p-0 bg-transparent">
        <a className="h4 mb-0 d-flex align-items-center text-inherit text-decoration-none py-3 px-4"
           href={`#${slugsService.kebabCase(chapter.title)}`}
           onClick={() => {
             setOpen(!open)
           }}
           role="button" aria-expanded="false"
           aria-controls={slugsService.kebabCase(chapter.title)}>
          <div className="me-auto">
            {chapter.title}
            <p className="mb-0 text-muted fs-6 mt-1 fw-normal">{`${chapter.lessons.length} ${getNoun(chapter.lessons.length, 'урок', 'урока', 'уроков')}`}</p>
          </div>
          <span className="chevron-arrow ms-4">
            <i className={`fe fe-chevron-${open ? 'up' : 'down'} fs-4`}></i>
          </span>
        </a>

        <div className={`collapse ${open ? 'show' : ''}`} id={slugsService.kebabCase(chapter.title)}>
          <ul className="list-group list-group-flush">

            {chapter.lessons.map((lesson, index) => {
              if (lesson.id == currentLessonId) {
                return <LessonRow key={index} lesson={lesson} passed={false} current={true}/>
              }
              
              if (passedLessonIds.includes(lesson.id)) {
                return <LessonRow key={index} lesson={lesson} passed={true} current={false}/>
              }

              return <LessonRow key={index} lesson={lesson} passed={false} current={false}/>
            })}
          </ul>
        </div>
      </li>
  );
};

export default LessonsChapterRow;