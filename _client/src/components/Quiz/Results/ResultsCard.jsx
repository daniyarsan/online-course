import React from 'react';
import {trimString} from "../../../service/Display.js";

const ResultsCard = ({result}) => {

  return (
      <tr>
        <td>{result?.quiz?.title}</td>
        <td>{result.score}</td>
        <td>
          {result.isResultBad() && <span className="badge bg-danger">Неудовлетворительно</span>}
          {result.isResultAverage() && <span className="badge bg-warning">Удовлетворительно</span>}
          {result.isResultGood() && <span className="badge bg-success">Отлично</span>}
        </td>
        <td>{result.getTimeSpent()}</td>
        <td>
          <a href="" className="fe fe-eye"></a>
        </td>
      </tr>
  )
}

export default ResultsCard;