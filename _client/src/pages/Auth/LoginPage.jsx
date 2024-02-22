import React from 'react'
import Login from "../../components/Authorization/Login.jsx";

export const LoginPage = () => {

  return (
      <section className="container d-flex flex-column">
        <div className="row align-items-center justify-content-center g-0 min-vh-100">
          <div className="col-lg-5 col-md-8 py-8 py-xl-0">
            <Login/>
          </div>
        </div>
      </section>
  )
}
