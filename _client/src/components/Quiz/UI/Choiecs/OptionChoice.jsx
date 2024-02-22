import React from 'react';
import {QUESTION_TYPE_OPTION, QUESTION_TYPE_TEXT} from "../../../../api/_const.js";

const OptionChoice = ({choice, answer, handleResponseEvent}) => {

  return (
      <div key={choice.id} className="list-group-item list-group-item-action">
        <div className="form-check" onClick={() => handleResponseEvent(QUESTION_TYPE_OPTION, choice.id)}>
          <input className="form-check-input" type="radio" checked={choice.id === answer?.payload} onChange={() => {
          }}/>
          <label className="form-check-label stretched-link">{choice.description}</label>
        </div>
      </div>
  )
}

export default OptionChoice;