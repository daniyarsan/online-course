let BASE_URL = import.meta.env.VITE_API_URL

if (window.location.hostname.includes('localhost')) {
  BASE_URL = import.meta.env.VITE_API_DEV_URL
}


export const REGISTER_URL = '/registration'
export const REFRESH_URL = '/api/token/refresh'
export const LOGIN_URL = '/api/login_check'
export const DEPARTMENT_URL = '/api/departments'

export const USERINFO_URL = '/api/user/userinfo'
export const QUIZ_DOMAIN = '/api/quiz'
export const COURSE_DOMAIN = '/api/course'
export const LESSON_DOMAIN = '/api/lesson'
export const RESULTS_URL = '/api/results'
export const STATISTICS_URL = '/api/statistics'
export const CONTACT_URL = '/api/contact'
export const ANON_CONTACT_URL = '/api/contact/anon'

/* CONSTANTS */
export const AUTH_TOKEN = 'AUTH_TOKEN'

/* CONST VALUES */
export const VALIDATION_MIN_PASSWORD_LENGTH = 5
export const VALIDATION_MAX_TEXTAREA_LENGTH = 300

export const QUESTION_TYPE_OPTION = 'option'
export const QUESTION_TYPE_TEXT = 'text'

export {BASE_URL}
