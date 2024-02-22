import {secondsToTimer} from "../service/TimeConverter.js";

export default class Quiz {

  id
  title
  description
  questions
  timeLimit
  createdAt


  constructor(rawObj) {
    Object.assign(this, rawObj);
  }

  getQuizTimeSeconds() {
    return this.timeLimit * 60
  }

  getQuizTime() {
    return secondsToTimer(this.getQuizTimeSeconds())
  }

  getSpentTimeSeconds(time) {
    return this.getQuizTimeSeconds() - time
  }

  getTotalQuestionsCount() {
    return this.questions.length
  }

  getCurrentQuestion(current) {
    return this.questions.at(current)
  }
}