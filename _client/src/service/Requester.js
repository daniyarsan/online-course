import axios from "axios";
import {BASE_URL, REFRESH_URL} from "../api/_const.js";
import {toast} from "react-toastify";

export class Requester {
  requester
  accessToken
  refreshToken

  constructor(accessToken = false, refreshToken = false) {
    this.accessToken = accessToken
    this.refreshToken = refreshToken

    this.requester = axios.create({
      baseURL: BASE_URL,
    })

    this._initRequester()
  }

  async post(url, data) {
    const headers = {}
    headers['Content-Type'] = 'application/json'

    return await this.requester.post(url, data, {headers})
  }

  async get(url) {
    return await this.requester.get(url)
  }

  async postMultipart(url, data) {
    const headers = {}
    headers['Content-Type'] =  'multipart/form-data'
    return await this.requester.post(url, data, {headers})
  }

  setToken(token) {
    this.accessToken = token
  }

  getToken() {
    return this.accessToken
  }

  _initRequester() {
    this.requester.interceptors.request.use((config) => {
      if (this.accessToken) {
        config.headers["Authorization"] = `Bearer ${this.accessToken}`
      }
      return config
    })

    this.requester.interceptors.response.use(
        (response) => {return response},
        async (error) => {
          
          if (error.response.status == 401 && !error.response.data.message.toLowerCase().includes('invalid credentials')) {
            this._renewToken()
            return
          }

          toast.error(error?.response?.data?.message, {
            position: toast.POSITION.TOP_RIGHT,
            autoClose: 2000
          })

          return Promise.reject(error);
        }
    )
  }

  _renewToken() {
    const axiosInstance = axios.create({
      baseURL: BASE_URL
    })
    axiosInstance.post(REFRESH_URL, {refresh_token:  this.refreshToken}).then(({data}) => {
      const user = JSON.parse(localStorage.getItem('user'))
      localStorage.setItem("user", JSON.stringify({...user, accessToken: data.token}))
      location.reload()
    }).catch(err => {
      console.log('Wrong token update credentials')
    })
  }
}