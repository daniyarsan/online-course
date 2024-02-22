
export default class UserCourse {

  course
  currentLesson
  lessonResults

  constructor(rawObj) {
    Object.assign(this, rawObj);
  }

}