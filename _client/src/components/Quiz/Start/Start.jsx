import React, {useContext} from 'react';
import {DataContext} from "../../../contexts/DataProvider.jsx";

const Start = ({quiz}) => {
  const {timer, setTimer} = useContext(DataContext)

  return quiz && (<div className="card-body p-10">
    <div className="text-center">
      {/*<img src="../assets/images/svg/survey-img.svg" alt="survey" className="img-fluid" />*/}
      <div className="px-lg-18">
        <h1>{quiz.title}</h1>
        <p className="mb-0 d-block">{quiz.description}</p>
        <button type='button'
                className="btn btn-primary mt-4"
                onClick={() => {
                  setTimer({...timer, [quiz.id]: quiz.timeLimit * 60})
                }}>Приступить
        </button>
      </div>
    </div>
  </div>)
}

export default Start;