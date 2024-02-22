import React from 'react';
import {Field} from "formik";

const Select = ({name, options, ...props}) => {
  return (
      <Field
          className="form-control"
          component="select"
          id={name}
          name={name}
          {...props}
      >
        <option value="" disabled>Выберите...</option>
        { options.map(({text, value}) => <option key={value} value={value}>{text}</option>)}
      </Field>
  );
};

export default Select;