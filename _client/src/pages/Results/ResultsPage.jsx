import React from 'react';
import Results from "../../components/Quiz/Results/Results.jsx";

const ResultsPage = () => {


  return (
      <div className="card border-0">
        <div className="card-header d-lg-flex justify-content-between align-items-center">
          <div className="mb-3 mb-lg-0">
            <h3 className="mb-0">Мои результаты</h3>
            <p className="mb-0">
              Здесь хранится ваша история тестов
            </p>
          </div>
        </div>

        <Results />
      </div>
  );
};

export default ResultsPage;