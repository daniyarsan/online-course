import axios from 'axios'
import {AUTH_TOKEN, BASE_URL, REFRESH_URL} from './_const.js'

export const publicApi = axios.create({
  baseURL: BASE_URL,
})

/* This is for authorized JSON requests */
const api = axios.create({
  baseURL: BASE_URL,
});

api.interceptors.request.use((config) => {
  const tokenData = JSON.parse(localStorage.getItem(AUTH_TOKEN))
  if (tokenData) {
    config.headers["Authorization"] = `Bearer ${tokenData.token}`
    config.headers["Content-Type"] = 'application/json'
  }
  return config;
})

api.interceptors.response.use(
    (response) => {
      return response;
    },

    async (error) => {
      if (localStorage.getItem(AUTH_TOKEN)) {
        if (error.response?.status === 401) {
          const {refresh_token} = JSON.parse(localStorage.getItem(AUTH_TOKEN))
          const axiosInstance = axios.create({
            baseURL: BASE_URL,
          })

          axiosInstance.post(REFRESH_URL, {refresh_token}).then(({data}) => {
            localStorage.setItem(AUTH_TOKEN, JSON.stringify(data));
          }).catch(err => {console.log(err)});

          // return axiosInstance(error.config);
        } else {
          return Promise.reject(error);
        }
      }

      return error.response
    }
);

export default api;