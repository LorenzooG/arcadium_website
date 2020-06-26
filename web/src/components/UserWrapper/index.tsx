import React from 'react'

import { Container, Wrapper } from './styles'

const UserWrapper: React.FC = ({ children }) => {
  return (
    <Wrapper>
      <Container>{children}</Container>
    </Wrapper>
  )
}

export default UserWrapper
