import React from 'react'
import classes from './preloader.module.scss'


export const Preloader = () => {
  return (
      <div className={classes['loader-container']}>
        <div className={classes['loader']}></div>
      </div>
  )
}
