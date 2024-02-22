import React from 'react'
import {Link} from "react-router-dom";
import './Success.css'

function Success({total, correct}) {

  return (
      <div className="card">
        <div className="card-body p-10 text-center">
          <div className="mb-4 ">
            <h2>🎉 Поздравляем. Вы прошли тест. </h2>
            <p className="mb-0 px-lg-14">Все ответы вы найдете на странице пройденных тестов</p>
          </div>
          <div className="d-flex justify-content-center">
            <div className="resultChart"></div>

          </div>
          <div className="mt-3">
            {/*<span>Your Score: <span className="text-dark">85.83% (85.83 points)</span></span><br>*/}
            {/*<span className="mt-2 d-block">Passing Score: <span className="text-dark">80%</span></span>*/}
          </div>
          <div className="mt-5">
            <Link to='/' className="btn btn-primary">На главную</Link>
          </div>
        </div>
      </div>
  )
}

export default Success
