import Result from "./Result.js";
import {formatTime} from "../service/TimeConverter.js";

export default class StatisticsModel {
  rank
  countResults
  scoreTotal
  results
  userRegistered


  constructor(rawObj) {
    Object.assign(this, rawObj);
  }

  getUserRegistered() {
    return formatTime(this.userRegistered)
  }
}