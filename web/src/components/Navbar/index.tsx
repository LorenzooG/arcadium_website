import React, { useState } from 'react'

import { FiLogOut, FiMenu } from 'react-icons/fi'

import { Link } from 'react-router-dom'

import { useDispatch, useSelector } from 'react-redux'
import { RootState } from '~/store/modules'
import { logoutAction } from '~/store/modules/auth/actions'

import { requestPlayerHead } from '~/utils'

import { locale } from '~/services'
import { User } from '~/services/entities'

import {
  Container,
  ContainerWrapper,
  Item,
  List,
  LogOut,
  Nav,
  Toggler,
  User as UserStyle
} from './styles'

const NAV_ITEMS = [
  {
    link: '/',
    title: locale.getTranslation('page.home')
  },
  {
    link: '/products',
    title: locale.getTranslation('page.products')
  }
]

const Navbar: React.FC = () => {
  const isLogged = useSelector<RootState, boolean>(state => state.auth.isLogged)
  const user = useSelector<RootState, User | null>(state => state.auth.account)
  const [open, setOpen] = useState(false)

  const dispatch = useDispatch()

  return (
    <ContainerWrapper>
      <Container>
        <Toggler onClick={() => setOpen(!open)}>
          <FiMenu />
        </Toggler>
        <Nav open={open}>
          <List>
            {NAV_ITEMS.map((item, index) => (
              <Item key={index}>
                <Link to={item.link}>{item.title}</Link>
              </Item>
            ))}
          </List>
          {isLogged && user ? (
            <UserStyle>
              <div>
                <span>
                  {locale.getTranslation('message.logged.as')}{' '}
                  <strong>{user.userName}</strong>
                </span>
                <Link to={'/user'}>
                  <img
                    src={requestPlayerHead(user.userName)}
                    alt={user.userName}
                  />
                </Link>
              </div>
              <LogOut onClick={() => dispatch(logoutAction())}>
                <FiLogOut />
              </LogOut>
            </UserStyle>
          ) : (
            <Item>
              <Link to={'/login'}>{locale.getTranslation('action.login')}</Link>
            </Item>
          )}
        </Nav>
      </Container>
    </ContainerWrapper>
  )
}

export default Navbar
