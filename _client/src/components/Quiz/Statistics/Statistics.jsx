import React, {useContext, useEffect, useState} from 'react';
import {AuthContext} from "../../../contexts/AuthProvider.jsx";
import {RequestContext} from "../../../contexts/RequestProvider.jsx";
import {STATISTICS_URL} from "../../../api/_const.js";
import StatisticsModel from "../../../models/StatisticsModel.js";

const Statistics = () => {
  const { loader, setLoader } = useContext(AuthContext)
  const {requester} = useContext(RequestContext)
  const [statistics, setStatistics] = useState(new StatisticsModel())

  useEffect(() => {
    setLoader(true)
    requester.get(`${STATISTICS_URL}`).then(({data}) => {
      setStatistics(new StatisticsModel(data))
    }).catch((err) => {
      console.log(err)
    }).finally(() => {
      setLoader(false)
    })
  }, [])



  return (
      <div className="card-body">
        <div className="pt-0 pb-5">
          <div className="row mb-4">
            <div className="col-lg-6 col-md-8 col-7 mb-2 mb-lg-0">
              <span className="d-block">
                <span className="h4">Текущий статус: </span>
                <span className="badge bg-success ms-2">{statistics.rank}</span>
              </span>
              <p className="mb-0 fs-6">
                Пройдено тестов: {statistics.countResults}
              </p>
            </div>
            <div className="col-lg-3 col-md-4 col-5 mb-2 mb-lg-0">
            </div>
            <div className="col-lg-3 col-md-12 col-12 d-lg-flex align-items-start justify-content-end">
            </div>
          </div>

          <div className="row">
            <div className="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
              <span className="fs-6">Зарегестрирован</span>
              <h6 className="mb-0">{statistics.getUserRegistered()}</h6>
            </div>
            <div className="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
              <span className="fs-6">Сумарный Балл</span>
              <h6 className="mb-0">{statistics.scoreTotal}</h6>
            </div>
            <div className="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
              <span className="fs-6">Опция</span>
              <h6 className="mb-0">Что то еще</h6>
            </div>
            <div className="col-lg-3 col-md-3 col-6 mb-2 mb-lg-0">
              <span className="fs-6">Еще опция</span>
              <h6 className="mb-0">Что нибудь еще</h6>
            </div>
          </div>
        </div>
      </div>
  )

}

export default Statistics;