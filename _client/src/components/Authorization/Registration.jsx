import React, {useContext, useEffect, useState} from 'react'
import {ErrorMessage, Field, Form, Formik} from 'formik';
import {object, string, ref} from "yup";
import {REGISTER_URL, VALIDATION_MIN_PASSWORD_LENGTH} from "../../api/_const.js";
import {getDepartmentList} from "../../api/manager.js";
import Select from "../UI/Select/Select.jsx";
import {toast} from "react-toastify";
import {Link} from "react-router-dom";
import {Logo} from "../../assets/images/Logo.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../contexts/AuthProvider.jsx";

function Registration() {
  const {requester} = useContext(RequestContext);
  const { setLoader } = useContext(AuthContext);

  const [departmentOptions, setDepartmentOptions] = useState([])

  const initialValues = {username: '', department: '', plainPassword: '', confirmPassword: ''}

  const onSubmit = (data, formikHelpers) => {
    setLoader(true)

    requester.post(REGISTER_URL, JSON.stringify(data)).then(({data}) => {
      toast.success('Пользователь создан успешно', {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 2000
      })
      formikHelpers.resetForm()
    }).finally(() => {
      setLoader(false)
    })
  }

  const validation = object({
    username: string().required('Обязательное поле'), department: string().required('Обязательное поле'),
    plainPassword: string()
        .required('Обязательное поле').required('Обязательное поле')
        .min(VALIDATION_MIN_PASSWORD_LENGTH, `Не менее ${VALIDATION_MIN_PASSWORD_LENGTH} символов`)
        .matches(/[0-9]/, 'Должен содержать цифру')
        .matches(/[a-z]/, 'Должен содержать прописную букву')
        .matches(/[A-Z]/, 'Должен содержать заглавную букву')
        .matches(/[^\w]/, 'Должен содержать символ'),
    confirmPassword: string().required('Обязательное поле').oneOf([ref('plainPassword'), null], 'Пароли должны совпадать')
  })

  useEffect(() => {
    getDepartmentList().then(resp => {
      setDepartmentOptions(resp?.data.map(({id: value, name: text}) => ({text, value})))
    }).catch(err => {
      console.log(err)
    })
  }, [])


  return (
      <div className="card shadow">
        <div className="card-body p-6">
          <div className="mb-4">
            <Link to='/'><Logo/></Link>
            <h1 className="mb-1 fw-bold">Войти</h1>
            <span>Eсть учетная запись? <Link to='/login' className="ms-1">Войти</Link></span>
          </div>


          <Formik
              initialValues={initialValues}
              validationSchema={validation}
              onSubmit={onSubmit}>
            {({errors, isValid,handleSubmit, touched, dirty}) => (
                <Form size='large' onSubmit={handleSubmit}>

                  <div className="mb-3">
                    <label htmlFor="username" className="form-label">Логин</label>
                    <Field type='text' className="form-control" name="username" placeholder="Логин"/>
                    <ErrorMessage className="text-danger" name="username" component="small"/>
                  </div>

                  <div className="mb-3">
                    <label htmlFor="username" className="form-label">Отдел</label>
                    <Select name="department" placeholder="Отдел" options={departmentOptions} />
                    <ErrorMessage className="text-danger" name="department" component="small"/>
                  </div>


                  <div className="mb-3">
                    <label htmlFor="password" className="form-label">Пароль</label>
                    <Field className="form-control" type="password" name="plainPassword" placeholder="Пароль"/>
                    <ErrorMessage className="text-danger" name="plainPassword" component="small"/>
                  </div>

                  <div className="mb-3">
                    <Field className="form-control" type="password" name="confirmPassword" placeholder="Повторить пароль"/>
                    <ErrorMessage className="text-danger" name="confirmPassword" component="small"/>
                  </div>

                  <div className="d-grid">
                    <button type="submit" className={`btn btn-primary ${!isValid ? 'disabled' : '' }`}>Регистрация</button>
                  </div>

                </Form>
            )}
          </Formik>
        </div>
      </div>
  )
}

export default Registration
