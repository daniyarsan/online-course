
export default class Lesson {

  id
  title
  courseId
  content
  media
  mediaFile

  constructor(rawObj) {
    Object.assign(this, rawObj);
  }


}