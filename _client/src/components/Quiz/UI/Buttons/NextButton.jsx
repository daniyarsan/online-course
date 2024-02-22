import React from 'react';

const NextButton = ({disabled, handleNextClick}) => {

  return (
      <button className="btn btn-primary" onClick={handleNextClick} disabled={disabled}>
        Следующий <i className="fe fe-arrow-right"></i>
      </button>
  )
}

export default NextButton;