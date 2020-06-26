import React, { useEffect, useState } from 'react'

import produce from 'immer'
import { useResource } from '~/utils'

import Error from '~/components/ErrorComponent'
import Loading from './ProductList/Loading'
import ProductList from './ProductList'

import { products as service } from '~/services'
import { Product } from '~/services/entities'

import ContainerContext from './ProductContainerContext'

const AdminProductContainer: React.FC = () => {
  const [_products, loading, error] = useResource<Product[]>(service.fetchAll)
  const [products, setProducts] = useState(_products)

  useEffect(() => void setProducts(_products), [_products])

  if (loading) {
    return <Loading />
  }

  if (error) {
    return <Error />
  }

  return (
    <ContainerContext.Provider
      value={{
        updateProduct: (id, product) => {
          setProducts(
            produce(products, draft => {
              draft[
                products.findIndex(_product => _product.id === id)
              ] = product
            })
          )
        },
        addProduct: product => {
          setProducts(
            produce(products, draft => {
              draft.push(product)
            })
          )
        },
        deleteProduct: id => {
          setProducts(
            produce(products, draft => {
              draft.splice(
                products.findIndex(product => product.id === id),
                1
              )
            })
          )
        }
      }}
    >
      <ProductList products={products} />
    </ContainerContext.Provider>
  )
}

export default AdminProductContainer
