import React from 'react';
import Statistics from "../../components/Quiz/Statistics/Statistics.jsx";

const StatisticsPage = () => {


  return (
      <div className="card border-0">
        <div className="card-header d-lg-flex justify-content-between align-items-center">
          <div className="mb-3 mb-lg-0">
            <h3 className="mb-0">Ваши достижения</h3>
            <p className="mb-0">
              Здесь хранится информация о ваших достижениях
            </p>
          </div>
        </div>
        <Statistics />
      </div>
  );
};

export default StatisticsPage;