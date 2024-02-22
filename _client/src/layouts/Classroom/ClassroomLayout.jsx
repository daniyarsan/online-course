import {Outlet} from "react-router-dom";
import React, {useContext} from 'react'
import {ToastContainer} from "react-toastify";
import {Preloader} from "../../components/UI/Preloader/index.js";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import HeaderFixed from "../_parts/HeaderFixed.jsx";

function ClassroomLayout() {
  const { loader, setLoader } = useContext(AuthContext);

  return (
      <>
        {loader && (<Preloader />)}
        <HeaderFixed/>
        <main>
          <Outlet/>
        </main>
        <ToastContainer/>
      </>
  )
}

export default ClassroomLayout
