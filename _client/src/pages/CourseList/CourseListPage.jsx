import React from 'react'
import CoruseList from "../../components/CourseList/CourseList.jsx";

function CourseListPage() {

  return (
      <section className="pt-5 pb-5">
        <div className="container">
          <div className="row mt-0 mt-md-4">
            <div className="col-lg-12 col-md-8 col-12">
              <CoruseList/>
            </div>
          </div>
        </div>
      </section>
  )
}

export default CourseListPage
