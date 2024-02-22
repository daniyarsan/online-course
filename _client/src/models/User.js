import {formatTime} from "../service/TimeConverter.js";

export class User {

  id
  isAuthorized
  username
  accessToken
  avatar
  department
  refreshToken
  updatedAt
  createdAt


  constructor(rawObj) {
    Object.assign(this, rawObj);
  }

  getCreated() {
    return formatTime(this.createdAt)
  }
}
