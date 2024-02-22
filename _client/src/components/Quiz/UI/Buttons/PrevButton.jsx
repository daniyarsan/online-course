import React from 'react';

const PrevButton = ({handlePrevClick}) => {
  return (
      <button className="btn btn-primary" onClick={handlePrevClick}>
        <i className="fe fe-arrow-left"></i> Предыдущий
      </button>
  );
};

export default PrevButton;