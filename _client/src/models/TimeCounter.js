import {secondsToMinutes, secondsToTimer} from "../service/TimeConverter.js";

export class TimeCounter {
  seconds = false

  constructor(seconds) {
    this.seconds = seconds
  }

  getMinutes() {
    return secondsToMinutes(this.seconds)
  }

  getCountdownTimer() {
    if (!this.seconds) {
      return false
    }

    return secondsToTimer(this.seconds)
  }
}