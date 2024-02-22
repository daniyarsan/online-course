import React, {useContext, useState} from 'react'
import {Formik, Form, Field, ErrorMessage} from 'formik';
import {object, string} from "yup";
import {VALIDATION_MIN_PASSWORD_LENGTH} from "../../api/_const.js";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {Link} from "react-router-dom";
import {Logo} from "../../assets/images/Logo.jsx";

function Login() {
  const {user, loginAction} = useContext(AuthContext);

  const onSubmit = (input, formikHelpers) => {
    loginAction(input)
  }

  const initialValues = {
    username: '',
    password: ''
  }

  const validation = object({
    username: string().required('Обязательное поле'),
    password: string()
        .required('Обязательное поле')
        .min(VALIDATION_MIN_PASSWORD_LENGTH, `Пароль должен быть мин ${VALIDATION_MIN_PASSWORD_LENGTH} символов`)
  })

  return (
      <div className="card shadow">
        <div className="card-body p-6">
          <div className="mb-4">
            <Link to='/'><Logo/></Link>
            <h1 className="mb-1 fw-bold">Войти</h1>
            <span>Нет учетной записи? <Link to='/registration' className="ms-1">Регистрация</Link></span>
          </div>


          <Formik
              initialValues={initialValues}
              validationSchema={validation}
              onSubmit={onSubmit}>
            {({errors, isValid, touched, dirty}) => (
                <Form size='large'>
                  <div className="mb-3">
                    <label htmlFor="username" className="form-label">Логин</label>
                    <Field type='text' className="form-control" name="username" placeholder="Логин"/>
                    <ErrorMessage className="text-danger" name="username" component="small"/>
                  </div>

                  <div className="mb-3">
                    <label htmlFor="password" className="form-label">Пароль</label>
                    <Field className="form-control" type="password" name="password" placeholder="Пароль"/>
                    <ErrorMessage className="text-danger" name="password" component="small"/>
                  </div>

                  <div className="d-grid">
                    <button type="submit" className="btn btn-primary" disabled={!isValid || !dirty}>
                      Войти
                    </button>
                  </div>
                </Form>
            )}
          </Formik>
        </div>
      </div>

  )
}

export default Login
