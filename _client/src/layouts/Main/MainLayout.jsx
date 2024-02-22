import {Outlet} from "react-router-dom";
import React, {useContext} from 'react'
import Header from "../_parts/Header.jsx";
import {ToastContainer} from "react-toastify";
import Footer from "../_parts/Footer.jsx";
import {Preloader} from "../../components/UI/Preloader/index.js";
import {AuthContext} from "../../contexts/AuthProvider.jsx";

function MainLayout() {
  const { loader, setLoader } = useContext(AuthContext);

  return (
      <>
        {loader && (<Preloader />)}
        <Header/>
        <main>
          <Outlet/>
        </main>
        <Footer/>
        <ToastContainer/>
      </>
  )
}

export default MainLayout
