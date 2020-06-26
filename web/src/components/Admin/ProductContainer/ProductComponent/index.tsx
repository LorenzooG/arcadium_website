import React, { useState } from 'react'

import { FiEdit, FiTrash } from 'react-icons/fi'

import ProductEditModal from '~/components/Admin/ProductContainer/ProductEditModal'
import ProductDeleteModal from '~/components/Admin/ProductContainer/ProductDeleteModal'

import { Product } from '~/services/entities'

import { Container, DeleteButton, EditButton } from './styles'

type Props = {
  product: Product
}

const AdminProductComponent: React.FC<Props> = ({ product }) => {
  const [editOpen, setEditOpen] = useState(false)
  const [deleteOpen, setDeleteOpen] = useState(false)

  return (
    <Container key={product.id}>
      <ProductEditModal
        open={editOpen}
        setOpen={setEditOpen}
        product={product}
      />

      <ProductDeleteModal
        open={deleteOpen}
        setOpen={setDeleteOpen}
        id={product.id}
      />

      <span>{product.id}</span>

      <img src={product.image} alt={product.name} />

      <span>{product.name}</span>

      <div>
        <EditButton onClick={() => setEditOpen(true)}>
          <FiEdit />
        </EditButton>

        <DeleteButton onClick={() => setDeleteOpen(true)}>
          <FiTrash />
        </DeleteButton>
      </div>
    </Container>
  )
}

export default AdminProductComponent
