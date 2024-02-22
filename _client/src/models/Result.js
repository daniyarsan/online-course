import {formatTime, secondsToTimer} from "../service/TimeConverter.js";

export default class Result {

  id
  quiz
  timeSpent
  createdAt
  updatedAt
  score
  correctCount

  constructor(rawObj) {
    Object.assign(this, rawObj);
  }

  getCreated() {
    return formatTime(this.createdAt)
  }

  getTimeSpent() {
    return secondsToTimer(this.timeSpent)
  }

  isResultBad() {
    return ((this.score * 100) / this.quiz.maxPossibleScore) < 40
  }

  isResultAverage() {
    return ((this.score * 100) / this.quiz.maxPossibleScore) > 40 && ((this.score * 100) / this.quiz.maxPossibleScore) < 80
  }

  isResultGood() {
    return ((this.score * 100) / this.quiz.maxPossibleScore) > 80
  }
}