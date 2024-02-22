import React, {useContext, useEffect, useState} from "react";

const Course = ({course}) => {

  return (
      <>
        <div className="card mb-5">
          <div className="card-body">
            <div className="d-flex justify-content-between align-items-center">
              <h1 className="fw-semibold mb-2">
                {course.title}
              </h1>
              <a href="#" data-bs-toggle="tooltip" data-placement="top" title="Add to Bookmarks">
                <i className="fe fe-bookmark fs-3 text-inherit"></i>
              </a>
            </div>
            <div className="d-flex mb-5">
                <span>
                  <i className="mdi mdi-star me-n1 text-warning"></i>
                  <i className="mdi mdi-star me-n1 text-warning"></i>
                  <i className="mdi mdi-star me-n1 text-warning"></i>
                  <i className="mdi mdi-star me-n1 text-warning"></i>
                  <i className="mdi mdi-star-half-full text-warning"></i>
                  <span className="fw-medium">(140)</span>
                </span>

              <span className="ms-4 d-none d-md-block">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="8" width="2" height="6" rx="1" fill="#754FFE"></rect>
                            <rect x="7" y="5" width="2" height="9" rx="1" fill="#754FFE"></rect>
                            <rect x="11" y="2" width="2" height="12" rx="1" fill="#DBD8E9"></rect>
                          </svg>
                  <span>Intermediate</span>
                </span>

              <span className="ms-4 d-none d-md-block"><i className="mdi mdi-account-multiple-outline"></i><span>Enrolled</span></span>
            </div>
          </div>
          <ul className="nav nav-lt-tab" id="tab" role="tablist">
            <li className="nav-item">
              <a className="nav-link active" id="description-tab" data-bs-toggle="pill" href="#description" role="tab"
                 aria-controls="description" aria-selected="false">Description</a>
            </li>
          </ul>
        </div>

        <div className="card rounded-3">
          <div className="card-body">
            <div className="tab-content" id="tabContent">
              <div className="tab-pane fade show active" id="description" role="tabpanel">
                <div className="mb-4">
                  <h3 className="mb-2">Course Descriptions</h3>
                </div>
                <div dangerouslySetInnerHTML={{__html: course.description}}></div>
              </div>
            </div>
          </div>
        </div>
      </>
  )
}

export default Course