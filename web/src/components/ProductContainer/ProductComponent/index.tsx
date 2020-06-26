import React, { useState } from 'react'

import { useDispatch } from 'react-redux'
import { addToCartAction } from '~/store/modules/cart/actions'

import { toLocalePrice } from '~/utils'

import ProductDescriptionModal from '../ProductDescriptionModal'

import { locale } from '~/services'
import { Product } from '~/services/entities'

import { AddButton, Container, DescriptionButton } from './styles'

type Props = {
  product: Product
}

const ProductComponent: React.FC<Props> = ({ product }) => {
  const [open, setOpen] = useState(false)
  const dispatch = useDispatch()

  return (
    <Container>
      <ProductDescriptionModal
        product={product}
        setOpen={setOpen}
        open={open}
      />

      <img src={product.image} alt={product.name} />

      <h3>{product.name}</h3>

      <small>{toLocalePrice(product.price)}</small>

      <div>
        <AddButton onClick={() => dispatch(addToCartAction(product))}>
          {locale.getTranslation('action.add.to.cart').toUpperCase()}
        </AddButton>

        <DescriptionButton onClick={() => setOpen(true)}>
          {locale.getTranslation('message.description').toUpperCase()}
        </DescriptionButton>
      </div>
    </Container>
  )
}

export default ProductComponent
