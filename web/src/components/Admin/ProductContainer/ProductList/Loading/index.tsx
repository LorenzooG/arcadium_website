import React from 'react'

import ProductComponentLoading from '~/components/Admin/ProductContainer/ProductComponent/Loading'

import { Container, List } from '../styles'

const AdminProductListLoading: React.FC = () => {
  return (
    <Container>
      <List>
        {[1, 2, 3, 4, 5].map(key => (
          <ProductComponentLoading key={key} />
        ))}
      </List>
    </Container>
  )
}

export default AdminProductListLoading
