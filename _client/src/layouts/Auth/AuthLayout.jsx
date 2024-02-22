import {Outlet} from "react-router-dom";
import React from 'react'
import {ToastContainer} from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import Footer from "../_parts/Footer.jsx";

function AuthLayout() {
  return (
      <>
        <main>
          <Outlet/>
          <ToastContainer/>
        </main>
        <Footer/>
        <ToastContainer/>
      </>
  )
}

export default AuthLayout
