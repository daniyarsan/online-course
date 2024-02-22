import React, {useContext} from 'react';
import {object, string} from "yup";
import {CONTACT_URL, VALIDATION_MAX_TEXTAREA_LENGTH} from "../../api/_const.js";
import {ErrorMessage, Field, Form, Formik} from "formik";
import Textarea from "../UI/Textarea/Textarea.jsx";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {toast} from "react-toastify";

const Contact = () => {
  const { currentUser } = useContext(AuthContext)
  const {requester} = useContext(RequestContext)
  const {setLoader} = useContext(AuthContext)

  const onSubmit = (request, formikHelpers) => {
    setLoader(true)
    requester.post(`${CONTACT_URL}`, {...request, username: currentUser.username, department: currentUser.department}).then(({data}) => {
      toast.success('Сообщение отправлено!', {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: 3000
      })

      formikHelpers.resetForm()
    }).catch((err) => {
      console.log(err)
      // navigate('/')
    }).finally(() => {
      setLoader(false)
    })
  }

  const validation = object({
    salary: string().matches(
        /(?=.*?\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|\d+)?(\.\d{1,2})?$/,
        "Введите цифровое значение"
    ),
    message: string().max(VALIDATION_MAX_TEXTAREA_LENGTH, `Должен быть не длинее ${VALIDATION_MAX_TEXTAREA_LENGTH} символов`)
  })

  return (
      <Formik
          initialValues={{
            position: '',
            salary: '',
            message: ''
          }}
          validationSchema={validation}
          onSubmit={onSubmit}
      >
        {({errors, isValid, touched, dirty}) => (
            <Form size='large'>
              <div className="row gx-3">
                <div className="mb-3 col-6 col-md-6">
                  <label className="form-label" htmlFor="fname">Ваша должность</label>
                  <Field
                      type='text'
                      className='form-control'
                      name="position"
                      placeholder="Топ менедж..."
                  />
                  <ErrorMessage className="text-danger" name="position" component="small"/>
                </div>

                <div className="mb-3 col-6 col-md-6">
                  <label className="form-label" htmlFor="fname">Размер оклада (₽)</label>
                  <Field
                      type='text'
                      className='form-control'
                      name="salary"
                      placeholder="10000..."
                  />
                  <ErrorMessage className="text-danger" name="salary" component="small"/>
                </div>

                <div className="mb-3 col-12 col-md-12">
                  <label className="form-label" htmlFor="fname">Ваше сообщение</label>
                  <Textarea
                      name="message"
                      placeholder="Ваше сообщение"
                  />

                </div>
                <div className="mb-3 col-12 col-md-12 d-flex justify-content-end">
                  <button className="btn btn-primary"  disabled={!isValid || !dirty} >
                    Отправить
                  </button>
                </div>
              </div>

            </Form>
        )}
      </Formik>
  )
}

export default Contact;