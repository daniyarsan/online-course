import React, {useContext} from 'react';
import {Link} from "react-router-dom";
import {DataContext} from "../../contexts/DataProvider.jsx";
import {TimeCounter} from "../../models/TimeCounter.js";

const QuizItem = ({quiz}) => {
  const {timer, setTimer} = useContext(DataContext)
  const timeCounter = new TimeCounter(timer[quiz.id])

  return (
      <tr>
        <td>
          <div className="d-flex align-items-center">
            <div>
              <a href="#">
                {/*<img src="../assets/images/course/course-wordpress.jpg" alt="course" className="rounded img-4by3-lg"/>*/}
              </a>
            </div>
            <div className="ms-3">
              <h4 className="mb-1 h5">{quiz.title}</h4>
              <ul className="list-inline fs-6 mb-0">
                <li className="list-inline-item">
                  {quiz.description}
                </li>
              </ul>
            </div>
          </div>
        </td>
        <td>{quiz.getTotalQuestionsCount()}</td>
        <td>
          {timeCounter.getCountdownTimer()
              ? <span className="text-warning"><i className="mdi mdi-clock"></i> {timeCounter.getCountdownTimer()}</span>
              : <span className="text-default"><i className="mdi mdi-clock"></i> {quiz.getQuizTime()}</span>}
        </td>

        {/*<td>*/}
        {/*  <span className="badge bg-success">Live</span>*/}
        {/*</td>*/}
        <td>
          <Link to={`/quiz/${quiz.id}`} className="btn btn-primary btn-sm">{timeCounter.getCountdownTimer() ? `Продолжить...` : `Начать тест`} </Link>
        </td>
      </tr>
  )
}



export default QuizItem;