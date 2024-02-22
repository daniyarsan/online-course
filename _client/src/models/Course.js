import {secondsToTimer} from "../service/TimeConverter.js";

export default class Course {

  id
  title
  chapters
  description
  createdAt
  courseCategory

  constructor(rawObj) {
    Object.assign(this, rawObj);
  }


}