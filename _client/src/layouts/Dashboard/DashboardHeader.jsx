import '../_parts/Header.css'
import React, {useContext} from "react";
import profileBg from '../../assets/images/background/profile-bg.jpg'
import {AuthContext} from "../../contexts/AuthProvider.jsx";

function DashboardHeader() {
  const { currentUser, logoutAction } = useContext(AuthContext);

  return (
      <>
        <div className="pt-16 rounded-top" style={{background: `url('${profileBg}') no-repeat`, backgroundSize: "cover"}}>
        </div>
        <div className="card px-4 pt-2 pb-4 shadow-sm rounded-top-0 rounded-bottom-0 rounded-bottom-md-2 ">
          <div
              className="d-flex align-items-end justify-content-between  ">
            <div className="d-flex align-items-center">
              <div className="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                {currentUser.avatar && (
                  <span style={{width: '100px', height: '100px'}} dangerouslySetInnerHTML={{__html: currentUser.avatar}}></span>
                )}
                {/*<img src={currentUser.avatar} className="avatar-xl rounded-circle border border-4 border-white" alt="avatar" />*/}
              </div>

              <div className="lh-1">
                <h2 className="mb-0">{currentUser.username}</h2>
                <p className=" mb-0 d-block">{currentUser?.department?.name}</p>
              </div>
            </div>
            <div>
              {/*<a href="profile-edit.html" className="btn btn-primary btn-sm d-none d-md-block">Account Setting</a>*/}
            </div>
          </div>
        </div>
      </>
  )
}

export default DashboardHeader

