import api, {publicApi} from './axios';
import {ANON_CONTACT_URL, BASE_URL, CONTACT_URL, DEPARTMENT_URL, LOGIN_URL, QUIZ_DOMAIN, RESULTS_URL, USERINFO_URL} from './_const';
import axios from "axios";

export const login = async (data) => {
    return await api.post(`${LOGIN_URL}`, data)
}
export const getUserData = async () => {
    return await api.get(`${USERINFO_URL}`)
}
export const getQuizList = async () => {
    return await api.get(`${QUIZ_DOMAIN}`)
}
export const getQuiz = async (id) => {
    return await api.get(`${QUIZ_DOMAIN}/${id}`)
}
export const getResults = async () => {
    return await api.get(`${RESULTS_URL}`)
}

export const getDepartmentList = async () => {
    return await publicApi.get(`${BASE_URL}${DEPARTMENT_URL}`);
}

export const sendQuiz = async (id, data) => {
    return await api.post(`${QUIZ_DOMAIN}/${id}`, data)
}

export const sendMessage = async (data) => {
    return await api.post(`${CONTACT_URL}`, data)
}

export const sendAnonMessage = async (data) => {
    return await api.post(`${ANON_CONTACT_URL}`, data)
}


// export const checkMail = async (data) => {
//     return await axios.create({
//         baseURL: API,
//         headers: {
//             'Content-Type': 'application/json'
//         },
//     }).post(`user/checkemail`, data);
// }
//
// // Save login for last connection
// export const saveLogin = async () => {
//     const token = localStorage.getItem('session');
//
//     return await axios.create({
//         baseURL: API,
//         headers: {
//             'Content-Type': 'application/json',
//             'Authorization': 'Bearer ' + token
//         },
//     }).put(`api/user/connection`, {});
// }
//
// // Register
// export const register = async (data) => {
//     return await axios.create({
//         baseURL: API,
//         headers: {
//             'Content-Type': 'application/json'
//         },
//     }).post(`register`, data);
// }
//
//
// // Reset password
// export const resetPwd = async (pwd, token) => {
//     return await axios.create({
//         baseURL: API,
//         headers: {
//             'Content-Type': 'application/json'
//         },
//     }).put(`user/reset-password`, {
//         password: pwd,
//         token: token
//     });
// }