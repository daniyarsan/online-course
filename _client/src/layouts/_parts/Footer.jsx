import React from 'react'
import {Link} from "react-router-dom";
import './Header.css'

function Footer() {

  return (
      <footer className="footer">
        <div className="container">
          <div className="row align-items-center g-0 border-top py-2">
            <div className="col-md-6 col-12 text-center text-md-start">
                <span>© <span id="copyright">
                </span>YourQuiz. All Rights Reserved.</span>
            </div>
            <div className="col-12 col-md-6">
              <nav className="nav nav-footer justify-content-center justify-content-md-end">
                <Link to='/contact' className="nav-link active ps-0" ><i className='mdi mdi-pencil'></i> Написать руководству</Link>
              </nav>
            </div>
          </div>
        </div>
      </footer>
  )
}

export default Footer

