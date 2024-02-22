import React, {useState} from 'react'
import {Preloader} from "../../components/UI/Preloader/index.js";
import AnonContact from "../../components/Contact/AnonContact.jsx";
import Contact from "../../components/Contact/Contact.jsx";
import Login from "../../components/Authorization/Login.jsx";

function AnonContactPage() {

  return (
      <section className="container d-flex flex-column">
        <div className="row align-items-center justify-content-center g-0 min-vh-100">
          <div className="col-lg-5 col-md-8 py-8 py-xl-0">
            <div className="card">
              <div className="card-header">
                <h3 className="mb-0">Сообщение</h3>
                <p className="mb-0">
                  Отправить сообщение руководству
                </p>
              </div>
              <div className="card-body">
                <AnonContact />
              </div>
            </div>
          </div>
        </div>
      </section>


  )
}

export default AnonContactPage
