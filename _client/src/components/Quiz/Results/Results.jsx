import React, {useContext, useEffect, useState} from 'react';
import ResultsCard from "./ResultsCard.jsx";
import {AuthContext} from "../../../contexts/AuthProvider.jsx";
import {RequestContext} from "../../../contexts/RequestProvider.jsx";
import {RESULTS_URL} from "../../../api/_const.js";
import Result from "../../../models/Result.js";
import {useNavigate} from "react-router-dom";

const Results = () => {
  const navigate = useNavigate()
  const { loader, setLoader } = useContext(AuthContext)
  const {requester} = useContext(RequestContext)
  const [results, setResults] = useState([])


  useEffect(() => {
    setLoader(true)
    requester.get(`${RESULTS_URL}`).then(({data}) => {
      setResults(data.map(result => new Result(result)))
    }).catch((err) => {
      navigate('/')
    }).finally(() => {
      setLoader(false)
    })
  }, [])


  return (
      <div className="table-invoice table-responsive">
        <table className="table mb-0 text-nowrap table-centered table-hover">
          <thead className="table-light">
          <tr>
            <th scope="col">Название</th>
            <th scope="col">Балл</th>
            <th scope="col">Статус</th>
            <th scope="col">Время</th>
            <th scope="col"></th>
          </tr>
          </thead>
          <tbody>
            {results?.map((result, index) => <ResultsCard key={index} result={result}/>)}
          </tbody>
        </table>
      </div>
  )
}

export default Results;