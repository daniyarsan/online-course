import React, {createContext, useEffect, useState} from "react";
import store from "../store/store.js";

export const DataContext = createContext()

export const DataProvider = ({ children }) => {
  const [counterCollection, setCounterCollection, updateCounterCollection] = store.useState("counterCollection");

  const [timer, setTimer] = useState(counterCollection)
  setCounterCollection(timer)


  return (
      <DataContext.Provider value={{ timer, setTimer }}>
        {children}
      </DataContext.Provider>
  )
}