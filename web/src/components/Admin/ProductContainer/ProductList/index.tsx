import React, { useState } from 'react'

import { FiPlusCircle } from 'react-icons/all'

import ProductComponent from '../ProductComponent'
import ProductEditModal from '../ProductEditModal'

import { Product } from '~/services/entities'

import { AddProductButton, Container, List } from './styles'

type Props = {
  products: Product[]
}

const AdminUserList: React.FC<Props> = ({ products }) => {
  const [open, setOpen] = useState(false)

  return (
    <Container>
      <ProductEditModal create open={open} setOpen={setOpen} />

      <List>
        {products.map(product => (
          <ProductComponent product={product} key={product.id} />
        ))}

        <li>
          <AddProductButton onClick={() => setOpen(true)}>
            <FiPlusCircle />
          </AddProductButton>
        </li>
      </List>
    </Container>
  )
}

export default AdminUserList
