import React from 'react';
import OptionChoice from "../UI/Choiecs/OptionChoice.jsx";
import {QUESTION_TYPE_TEXT} from "../../../api/_const.js";
import TextChoice from "../UI/Choiecs/TextChoice.jsx";


const Question = ({question, answer, handleResponseEvent}) => {
  return (
      <div className="mt-5">
        <h3 className="mb-3 mt-1">{question.title}</h3>
        <div className="list-group">
          {question.choices.map(choice => {
            if (choice.type === QUESTION_TYPE_TEXT) {
              return <TextChoice key={choice.id} choice={choice} answer={answer} handleResponseEvent={handleResponseEvent}/>
            }
            return <OptionChoice key={choice.id} choice={choice} answer={answer} handleResponseEvent={handleResponseEvent}/>
          })}
        </div>
      </div>
  );
};

export default Question;