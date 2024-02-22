import React, {useContext, useEffect} from 'react'
import {DataContext} from "../../../contexts/DataProvider.jsx";
import {TimeCounter} from "../../../models/TimeCounter.js";

export const Timer = ({id}) => {
  const {timer, setTimer} = useContext(DataContext)
  const timeCounter = new TimeCounter(timer[id])

  useEffect(() => {
    if (timeCounter.seconds > 0) {
      setTimeout(() => {
        setTimer({...timer, ...{[id] : timeCounter.seconds - 1}})
      }, 1000)
    }
  }, [timeCounter.seconds]);


  return (
      <div>
        <span className={timeCounter.getMinutes() < 1 ? `text-danger` : `text-success`}><i className="fe fe-clock me-1 align-middle"></i>{timeCounter.getCountdownTimer()}</span>
      </div>
  )
}