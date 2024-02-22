import React, {useState} from 'react'
import Registration from "../../components/Authorization/Registration.jsx";

export const RegistrationPage = () => {

  return (
      <section className="container d-flex flex-column">
        <div className="row align-items-center justify-content-center g-0 min-vh-100">
          <div className="col-lg-5 col-md-8 py-8 py-xl-0">
            <Registration />
          </div>
        </div>
      </section>
  )
}
