import React from 'react';

const SendButton = ({handleSendClick}) => {
  return (
      <button className="btn btn-primary" onClick={handleSendClick}>
        <i className="fe fe-mail"></i> Отправить
      </button>
  );
}

export default SendButton;