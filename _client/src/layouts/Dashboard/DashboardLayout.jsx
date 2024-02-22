import {Outlet} from "react-router-dom";
import React, {useContext} from 'react'
import Header from "../_parts/Header.jsx";
import {ToastContainer} from "react-toastify";
import Footer from "../_parts/Footer.jsx";
import Sidebar from "../_parts/Sidebar.jsx";
import DashboardHeader from "./DashboardHeader.jsx";
import {Preloader} from "../../components/UI/Preloader/index.js";
import {AuthContext} from "../../contexts/AuthProvider.jsx";

function DashboardLayout() {
  const { loader, setLoader } = useContext(AuthContext);

  return (
      <>
        {loader && (<Preloader />)}
        <Header/>
        <main>
          <section className="pt-5 pb-5">
            <div className="container">
              <div className="row align-items-center">
                <div className="col-xl-12 col-lg-12 col-md-12 col-12">
                  <DashboardHeader/>
                </div>
              </div>

              <div className="row mt-0 mt-md-4">
                <div className="col-lg-3 col-md-4 col-12">
                  <Sidebar/>
                </div>

                <div className="col-lg-9 col-md-8 col-12 position-relative overflow-hidden">
                  <Outlet/>
                </div>
              </div>
            </div>
          </section>
        </main>

        <Footer/>
        <ToastContainer/>
      </>

  )
}

export default DashboardLayout
