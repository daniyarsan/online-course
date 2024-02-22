import React, {useContext, useEffect, useState} from 'react'
import {useNavigate} from "react-router-dom";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {QUIZ_DOMAIN} from "../../api/_const.js";
import QuizItem from "./QuizItem.jsx";
import QuizModel from "../../models/Quiz.js";

function QuizList() {
  const navigate = useNavigate()
  const {setLoader} = useContext(AuthContext)
  const {requester} = useContext(RequestContext)
  const [quizCollection, setQuizCollection] = useState([])

  useEffect(() => {
    setLoader(true)
    requester.get(`${QUIZ_DOMAIN}`).then(({data}) => {
      setQuizCollection(data.map(item => new QuizModel(item)).filter(quiz => {
        return quiz.questions.length > 0
      }))
    }).catch((err) => {
      navigate('/')
    }).finally(() => {
      setLoader(false)
    })
  }, [])


  return (
      <div className="card mb-4">
        <div className="card-header">
          <h3 className="mb-0">Тесты</h3>
          <span>Доступ к образовательному центру "Мега"</span>
        </div>


        <div className="table-responsive overflow-y-hidden">
          <table className="table mb-0 text-nowrap table-hover table-centered text-nowrap">
            <thead className="table-light">
            <tr>
              <th scope="col">Курсы</th>
              <th scope="col">Кол-во вопросов</th>
              <th scope="col">Время осталось</th>
              {/*<th scope="col">Status</th>*/}
              <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

            {quizCollection.map((quiz) => <QuizItem key={quiz.id} quiz={quiz} />)}

            </tbody>
          </table>
        </div>
      </div>
  )
}

export default QuizList
