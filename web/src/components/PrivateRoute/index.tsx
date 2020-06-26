import React from 'react'

import { Redirect, Route, RouteProps } from 'react-router'

import { useSelector } from 'react-redux'
import { RootState } from '~/store/modules'

type Props = {
  onlyAdmin?: boolean
} & RouteProps

const PrivateRoute: React.FC<Props> = ({ onlyAdmin, ...props }) => {
  const isLogged = useSelector<RootState, boolean>(state => state.auth.isLogged)

  const isAdmin = useSelector<RootState, boolean>(state => state.auth.isAdmin)

  if (!isLogged) {
    return <Redirect to={'/'} />
  }

  if (onlyAdmin && !isAdmin) {
    return <Redirect to={'/'} />
  }

  return <Route {...props} />
}

export default PrivateRoute
