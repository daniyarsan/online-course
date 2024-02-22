import React from 'react'
import Contact from "../../components/Contact/Contact.jsx";

function ContactPage() {

  return (
      <div className="card">
        <div className="card-header">
          <h3 className="mb-0">Сообщение</h3>
          <p className="mb-0">
            Отправить сообщение руководству
          </p>
        </div>
        <div className="card-body">

          <Contact />
        </div>
      </div>
  )
}

export default ContactPage
