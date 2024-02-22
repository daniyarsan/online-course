import React from 'react';

const Progress = ({value}) => {

  const progressValue = value > 100 ? 100 : value;
  return (
      <div className="progress" style={{height: '6px'}}>
        <div className="progress-bar bg-success" style={{width: progressValue + '%'}} role="progressbar" aria-valuenow="20" aria-valuemin="10" aria-valuemax="100"></div>
      </div>
  );
};

export default Progress;