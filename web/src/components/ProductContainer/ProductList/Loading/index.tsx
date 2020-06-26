import React from 'react'

import { Container } from '../styles'
import ProductLoading from '~/components/ProductContainer/ProductComponent/Loading'

const ProductListLoading: React.FC = () => {
  return (
    <Container>
      <ul>
        {[1, 2, 3, 4, 5, 6].map(key => (
          <ProductLoading key={key} />
        ))}
      </ul>
    </Container>
  )
}

export default ProductListLoading
