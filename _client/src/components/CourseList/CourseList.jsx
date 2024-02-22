import React, {useContext, useEffect, useState} from 'react'
import {useNavigate} from "react-router-dom";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {COURSE_DOMAIN} from "../../api/_const.js";
import CourseItem from "./CourseItem.jsx";
import CourseModel from "../../models/Course.js";
import UserCourse from "../../models/UserCourse.js";
import {toast} from "react-toastify";

function CourseList() {
  const navigate = useNavigate()
  const {setLoader} = useContext(AuthContext)
  const {requester} = useContext(RequestContext)
  const [courses, setCourses] = useState([])
  const [userCourses, setUserCourses] = useState([])

  useEffect(() => {
    setLoader(true)
    requester.get(`${COURSE_DOMAIN}`).then(({data}) => {
      const {courses, userCourses} = data
      setCourses(courses)
      setUserCourses(userCourses)
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
      <div className="card mb-4">
        <div className="card-header">
          <h3 className="mb-0">Доступные курсы</h3>
          <span>Доступ к образовательному центру "Мега"</span>
        </div>
        <div className="card-body">
          <form className="row gx-3">
            <div className="col-lg-9 col-md-7 col-12 mb-lg-0 mb-2">
              <input type="search" className="form-control" placeholder="Поиск"/>
            </div>

            <div className="col-lg-3 col-md-5 col-12">
              <select className="form-control">
                <option value="">Date Created</option>
                <option value="Newest">Newest</option>
                <option value="High Rated">High Rated</option>
                <option value="Law Rated">Law Rated</option>
                <option value="High Earned">High Earned</option>
              </select>
            </div>
          </form>
        </div>

        <div className="table-responsive overflow-y-hidden">
          <table className="table mb-0 text-nowrap table-hover table-centered text-nowrap">
            <thead className="table-light">
            <tr>
              <th scope="col">Курсы</th>
              <th scope="col">Кол-во уроков</th>
              <th scope="col">Пройдено</th>
              {/*<th scope="col">Status</th>*/}
              <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

              {courses.map((course) => <CourseItem key={course.id} course={course} userCourses={userCourses} />)}

            </tbody>
          </table>
        </div>
      </div>
  )
}

export default CourseList
