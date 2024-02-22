import React, {Fragment, useContext, useState} from 'react'
import {Link, useNavigate} from "react-router-dom";
import {Logo} from "../../assets/images/Logo.jsx";
import './Header.css'
import {AuthContext} from "../../contexts/AuthProvider.jsx";

function Header() {
  const { currentUser, logoutAction } = useContext(AuthContext);
  const [active, setActive] = useState(window.location.pathname)

  return (
      <nav className="navbar navbar-expand-lg">
        <div className="container-fluid px-0">
          <Link to='/' className="navbar-brand"><Logo/></Link>

          <div className="ms-auto d-flex align-items-center order-lg-3">
            <ul className="navbar-nav navbar-right-wrap mx-2 flex-row">
              <li className="dropdown ms-2 d-inline-block position-static">
                <a className="rounded-circle" href="#" data-bs-toggle="dropdown" data-bs-display="static"
                   aria-expanded="false">
                  <div className="avatar avatar-md avatar-indicators avatar-online">
                    {currentUser.avatar && (
                      <span style={{width: '100px', height: '100px'}} dangerouslySetInnerHTML={{__html: currentUser.avatar}}></span>
                    )}
                    {/*<img alt="avatar" src="../assets/images/avatar/avatar-1.jpg" className="rounded-circle"/>*/}
                  </div>
                </a>
                <div className="dropdown-menu dropdown-menu-end position-absolute mx-3 my-5">
                  <div className="dropdown-item">
                    <div className="d-flex">
                      <div className="avatar avatar-md avatar-indicators avatar-online">
                        {currentUser.avatar && (
                          <span style={{width: '100px', height: '100px'}} dangerouslySetInnerHTML={{__html: currentUser.avatar}}></span>
                        )}
                        {/*<img alt="avatar" src="../assets/images/avatar/avatar-1.jpg" className="rounded-circle"/>*/}
                      </div>
                      <div className="ms-3 lh-1">
                        <h5 className="mb-1">{currentUser.username}</h5>
                        <p className="mb-0 text-muted">{currentUser?.department?.name}</p>
                      </div>
                    </div>
                  </div>
                  <div className="dropdown-divider"></div>
                  <ul className="list-unstyled">
                    {/*<li>*/}
                    {/*  <a className="dropdown-item" href="profile-edit.html">*/}
                    {/*    <i className="fe fe-user me-2"></i>Profile*/}
                    {/*  </a>*/}
                    {/*</li>*/}
                    <li>
                      <Link to='/statistics' className="dropdown-item">
                        <i className="fe fe-star me-2"></i>Статистика
                      </Link>
                    </li>
                    <li>
                      <Link to='/results' className="dropdown-item">
                        <i className="fe fe-calendar me-2"></i>Результаты тестов
                      </Link>
                    </li>
                    <li>
                      <Link to='/contact' className="dropdown-item">
                        <i className="fe fe-mail me-2"></i>Написать сообщение
                      </Link>
                    </li>
                  </ul>
                  <div className="dropdown-divider"></div>
                  <ul className="list-unstyled">
                    <li>
                      <a className="dropdown-item" onClick={() => {
                        logoutAction()
                        navigate('/')
                      }}>
                        <i className="fe fe-power me-2"></i>Выйти
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>

          </div>
          <div>
            <button className="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false"
                    aria-label="Toggle navigation">
              <span className="icon-bar top-bar mt-0"></span>
              <span className="icon-bar middle-bar"></span>
              <span className="icon-bar bottom-bar"></span>
            </button>
          </div>

          <div className="collapse navbar-collapse" id="navbar-default">
            <ul className="navbar-nav">

              <li className="nav-item dropdown">
                <a className="nav-link dropdown-toggle" href="#" id="navbarBrowse" data-bs-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" data-bs-display="static">
                  Образование
                </a>
                <ul className="dropdown-menu dropdown-menu-arrow" aria-labelledby="navbarBrowse">

                  <li className={`nav-item ${active === '/' ? 'active' : ''}`} onClick={() => setActive('/')}>
                    <Link to='/' className="dropdown-item">Тесты</Link>
                  </li>

                  <li className={`nav-item ${active === '/quiz' ? 'active' : ''}`} onClick={() => setActive('/quiz')}>
                    <Link to='/course' className="dropdown-item">Курсы</Link>
                  </li>

                </ul>
              </li>
            </ul>
            {/*<form className="mt-3 mt-lg-0 ms-lg-3 d-flex align-items-center">*/}
            {/*  <span className="position-absolute ps-3 search-icon">*/}
            {/*    <i className="fe fe-search"></i>*/}
            {/*  </span>*/}
            {/*  <input type="search" className="form-control ps-6" placeholder="Search Courses"/>*/}
            {/*</form>*/}

          </div>
        </div>
      </nav>
  )
}

export default Header