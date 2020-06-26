import React from 'react'

import { Container } from './styles'
import { Bar, Loading } from '~/styles'

const ProductLoading: React.FC = () => {
  return (
    <Container>
      <Bar size={'305px'}>
        <Loading />
      </Bar>
    </Container>
  )
}

export default ProductLoading
