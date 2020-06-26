import React from 'react'
import { Redirect, Route, RouteProps } from 'react-router'
import { useSelector } from 'react-redux'
import { RootState } from '~/store/modules'

const GuestRoute: React.FC<RouteProps> = ({ ...props }) => {
  const isLogged = useSelector<RootState, boolean>(state => state.auth.isLogged)

  if (isLogged) {
    return <Redirect to={'/'} />
  }

  return <Route {...props} />
}

export default GuestRoute
