import React from 'react'

import ProductComponent from '../ProductComponent'
import Empty from './Empty'

import { Product } from '~/services/entities'

import { Container } from './styles'

type Props = {
  products: Product[]
}

const ProductList: React.FC<Props> = ({ products }) => {
  return (
    <Container>
      {products.length > 0 ? (
        <ul>
          {products.map(product => (
            <ProductComponent key={product.id} product={product} />
          ))}
        </ul>
      ) : (
        <Empty />
      )}
    </Container>
  )
}

export default ProductList
