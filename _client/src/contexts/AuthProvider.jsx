import React, {createContext, useContext, useEffect, useState} from "react";
import store from "../store/store.js";
import {RequestContext} from "./RequestProvider.jsx";
import {LOGIN_URL, USERINFO_URL} from "../api/_const.js";
import {User} from "../models/User.js";

export const AuthContext = createContext()

export const AuthProvider = ({children}) => {
  const {requester} = useContext(RequestContext);

  /* CONTEXT STATES */
  const [user, setUser, updateUser] = store.useState("user");
  const [loader, setLoader] = useState(false);

  /* CONTEXT ACTIONS */
  const updateAction = () => {
    requester.get(`${USERINFO_URL}`).then(({data}) => {
      updateUser(user => {
        Object.keys(data).forEach(key => user[key] = data[key])
      })
    })
  }

  const loginAction = (data) => {
    requester.post(`${LOGIN_URL}`, data).then(({data}) => {
      requester.setToken(data.token)

      requester.get(`${USERINFO_URL}`).then((resp) => {
        const userData = resp.data
        userData.isAuthorized = true
        userData.accessToken = data.token
        userData.refreshToken = data.refresh_token
        setUser(userData)
      })
    })
  }

  const logoutAction = () => {
    setUser({})
    navigate('/')
  }

  const currentUser = new User(user)

  return (
      <AuthContext.Provider value={{currentUser, loginAction, logoutAction, loader, setLoader}} >
        {children}
      </AuthContext.Provider>
  )
}