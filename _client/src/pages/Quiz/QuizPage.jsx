import React from 'react'
import {useParams} from "react-router-dom";
import Quiz from "../../components/Quiz/Quiz.jsx";
import QuizList from "../../components/QuizList/QuizList.jsx";

function QuizPage() {
  const {id} = useParams()

  return (
      <section className="pt-5 pb-5">
        <div className="container">
          <div className="row mt-0 mt-md-4">
            <div className="col-lg-12 col-md-8 col-12">
              <Quiz id={id}/>
            </div>
          </div>
        </div>
      </section>
  )
}

export default QuizPage
