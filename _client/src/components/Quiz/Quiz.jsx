import './Quiz.css'

import React, {useContext, useEffect, useState} from "react";
import {Timer} from "./Timer/Timer.jsx";
import Progress from "../UI/Progress/Progress.jsx";
import {QUIZ_DOMAIN} from "../../api/_const.js";
import Success from "./Success/Success.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import Start from "./Start/Start.jsx";
import QuizModel from '../../models/Quiz.js'
import PrevButton from "./UI/Buttons/PrevButton.jsx";
import SendButton from "./UI/Buttons/SendButton.jsx";
import NextButton from "./UI/Buttons/NextButton.jsx";
import Question from "./Question/Question.jsx";
import {DataContext} from "../../contexts/DataProvider.jsx";
import {useNavigate} from "react-router-dom";

const Quiz = ({id}) => {
  const {requester} = useContext(RequestContext)
  const {setLoader} = useContext(AuthContext)
  const {timer, setTimer} = useContext(DataContext)
  const navigate = useNavigate()

  const currentTime = timer[id]
  const [quiz, setQuiz] = useState()
  const [currentStep, setCurrentStep] = useState(0)
  const [answerCollection, setAnswerCollection] = useState([])
  const [results, setResults] = useState(false)

  useEffect(() => {
    setLoader(true)
    requester.get(`${QUIZ_DOMAIN}/${id}`).then(({data}) => {
      setQuiz(new QuizModel(data))
    }).catch((err) => {
      navigate('/')
    }).finally(() => {
      setLoader(false)
    })
  }, [])

  useEffect(() => {
    if (currentTime <= 1) {
      sendResults()
    }
  }, [currentTime]);

  const sendResults = () => {
    requester.post(`${QUIZ_DOMAIN}/${id}`, {answers: answerCollection, time: quiz.getSpentTimeSeconds(currentTime)}).then(({data}) => {
      setResults(data)
    }).catch((err) => {
      console.log(err)
      navigate('/')
    }).finally(() => {
      setLoader(false)
    })
  }

  const handleNextClick = () => {
    setCurrentStep(currentStep >= quiz.getTotalQuestionsCount() - 1 ? quiz.getTotalQuestionsCount() - 1 : (currentStep + 1))
  }
  const handlePrevClick = () => {
    setCurrentStep(currentStep <= 0 ? 0 : (currentStep - 1))
  }
  const handleSendClick = () => {
    sendResults()
  }

  const handleResponseEvent = (type, choiceId) => {
    const currentQuestion = quiz.getCurrentQuestion(currentStep)

    const index = answerCollection.findIndex((item) => item.id === currentQuestion.id)
    if (index != -1) {
      setAnswerCollection([...answerCollection.slice(0, index), {id: currentQuestion.id, type: type, payload: choiceId}, ...answerCollection.slice(index + 1)])
    } else {
      setAnswerCollection([...answerCollection, {id: currentQuestion.id, type: type, payload: choiceId}])
    }
  }

  if (!currentTime) {
    return <Start quiz={quiz}/>
  }

  if (results) {
    return <Success total={quiz.getTotalQuestionsCount()} correct={results.correctCount}/>
  }

  return quiz && (
      <div id="courseForm">
        <div className="card mb-4">
          <div className="card-body">
            <div className="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
              <div className="d-flex align-items-center">
                {/*<a href="#"> <img src="../assets/images/course/course-react.jpg" alt="course" className="rounded img-4by3-lg" /></a>*/}
                <div className="ms-3">
                  <h3 className="mb-0"><a href="#" className="text-inherit">{quiz.title}</a></h3>
                </div>
              </div>

              <Timer id={quiz.id}/>
            </div>

            <div className="mt-3">
              <div className="d-flex justify-content-between"><span>Прогресс:</span>
                <span> Вопрос {currentStep + 1} из {quiz.getTotalQuestionsCount()}</span>
              </div>
              <div className="mt-2">
                <Progress value={(currentStep + 1) * 100 / quiz.getTotalQuestionsCount()}/>
              </div>
            </div>

            <Question question={quiz.getCurrentQuestion(currentStep)} answer={answerCollection[currentStep]} handleResponseEvent={handleResponseEvent} />
          </div>
        </div>


        <div className="mt-3 d-flex justify-content-between">
          <PrevButton handlePrevClick={handlePrevClick}/>
          {(currentStep + 1) !== quiz.getTotalQuestionsCount()
              ? <NextButton handleNextClick={handleNextClick} disabled={answerCollection[currentStep] == undefined}/>
              : <SendButton handleSendClick={handleSendClick} />}
        </div>

      </div>
  )
}

export default Quiz