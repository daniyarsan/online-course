import React from 'react';
import {QUESTION_TYPE_OPTION, QUESTION_TYPE_TEXT} from "../../../../api/_const.js";

const TextChoice = ({choice, answer, handleResponseEvent}) => {
  return (
        <div key={choice.id} className="list-group-item list-group-item-action">
          <div className='label'>{choice.description}</div>
          <textarea className="form-control" rows="10" value={answer?.type === QUESTION_TYPE_TEXT ? answer.payload :  ''} onChange={(e) => {
            handleResponseEvent(QUESTION_TYPE_TEXT, e.target.value)
          }}></textarea>
        </div>
    )
}

export default TextChoice;