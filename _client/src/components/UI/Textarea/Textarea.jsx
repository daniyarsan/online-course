import React from 'react';
import {useField} from "formik";


const Textarea = (props) => {
  const [field, meta] = useField(props);
  return (
      <>
        <textarea className="form-control" {...field} {...props} />
        {meta.touched && meta.error ? (
            <div className="error">{meta.error}</div>
        ) : null}
      </>
  )
};

export default Textarea;