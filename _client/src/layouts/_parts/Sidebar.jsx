import './Header.css'
import React, {useContext, useState} from "react";
import {Link} from "react-router-dom";
import {AuthContext} from "../../contexts/AuthProvider.jsx";

function Sidebar() {
  const [active, setActive] = useState(window.location.pathname)
  const {currentUser, logoutAction} = useContext(AuthContext);


  return (
      <nav className="navbar navbar-expand-md navbar-light shadow-sm mb-4 mb-lg-0 sidenav">
        <a className="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
        <button className="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light" type="button"
                data-bs-toggle="collapse" data-bs-target="#sidenav" aria-controls="sidenav" aria-expanded="false"
                aria-label="Toggle navigation">
          <span className="fe fe-menu"></span>
        </button>
        <div className="collapse navbar-collapse" id="sidenav">
          <div className="navbar-nav flex-column">

            <ul className="list-unstyled ms-n2 mb-4">
              <li className={`nav-item ${active === '/statistics' ? 'active' : ''}`} onClick={() => setActive('/statistics')}>
                <Link to='/statistics' className="nav-link"><i className="fe fe-star nav-icon"></i>Статистика</Link>
              </li>
              <li className={`nav-item ${active === '/results' ? 'active' : ''}`} onClick={() => setActive('/results')}>
                <Link to='/results' className="nav-link"><i className="fe fe-calendar nav-icon"></i>Результаты тестов</Link>
              </li>
            </ul>

            <span className="navbar-header">Настройки</span>

            <ul className="list-unstyled ms-n2 mb-0">
              <li className={`nav-item ${active === '/contact' ? 'active' : ''}`} onClick={() => setActive('/contact')}>
                <Link to='/contact' className="nav-link"><i className="fe fe-mail nav-icon"></i> Написать сообщение</Link>
              </li>

              <li className="nav-item">
                <a className="nav-link" onClick={() => {
                  logoutAction()
                  // navigate('/')
                }}><i className="fe fe-power nav-icon"></i>Выйти</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

  )
}

export default Sidebar

