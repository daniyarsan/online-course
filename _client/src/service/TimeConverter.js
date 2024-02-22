import moment from "moment/moment.js";

export const dateToTimestamp = (date) => {
  return moment(date).format()
}

export const formatTime = (date, format = "DD/MM/YYYY") => {
  const timestamp = dateToTimestamp(date)
  return moment(timestamp).format(format);
}

export const secondsToMinutes = (seconds) => {
  return  Math.floor(seconds / 60)
}

export const secondsToTimer = (seconds) => {
  const min =  (secondsToMinutes(seconds) < 10) ? '0' + secondsToMinutes(seconds) : secondsToMinutes(seconds)
  const sec =  ((seconds % 60) < 10) ? '0' + (seconds % 60) : seconds % 60

  return `${min} : ${sec}`
}