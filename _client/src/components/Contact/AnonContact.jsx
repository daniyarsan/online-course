import React, {useContext} from 'react';
import {object, string} from "yup";
import {ANON_CONTACT_URL, CONTACT_URL, VALIDATION_MAX_TEXTAREA_LENGTH} from "../../api/_const.js";
import {ErrorMessage, Field, Form, Formik} from "formik";
import {RequestContext} from "../../contexts/RequestProvider.jsx";
import {AuthContext} from "../../contexts/AuthProvider.jsx";
import {toast} from "react-toastify";
import Textarea from "../UI/Textarea/Textarea.jsx";

const AnonContact = () => {
  const {requester} = useContext(RequestContext)
  const {setLoader} = useContext(AuthContext)

  const onSubmit = (request, formikHelpers) => {
    setLoader(true)
    requester.post(`${ANON_CONTACT_URL}`, request).then(({data}) => {
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
    message: string().max(VALIDATION_MAX_TEXTAREA_LENGTH, `Должен быть не длинее ${VALIDATION_MAX_TEXTAREA_LENGTH} символов`)
  })

  return (
      <Formik
          initialValues={{
            message: ''
          }}
          validationSchema={validation}
          onSubmit={onSubmit}
      >
        {({errors, isValid, touched, dirty}) => (
            <Form size='large'>
              <div className="row gx-3">

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

export default AnonContact;