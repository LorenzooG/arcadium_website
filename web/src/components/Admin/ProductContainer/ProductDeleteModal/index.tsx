import React, { useContext } from 'react'
import DeleteEntityModal from '~/components/Admin/DeleteEntityModal'
import { errors, locale, products } from '~/services'
import { toast } from 'react-toastify'
import { toastMessage } from '~/utils'
import ProductContainerContext from '../ProductContainerContext'

type Props = {
  open: boolean
  setOpen: (value: boolean) => void
  id: number
}

const ProductDeleteModal: React.FC<Props> = ({ open, setOpen, id }) => {
  const context = useContext(ProductContainerContext)

  async function handleSubmit() {
    const localeEntity = locale.getTranslation('entity.product')

    const localeAction = locale
      .getTranslation('action.delete.entity')
      .replace('$entity', localeEntity)

    const localeTryNotification = locale.getTranslation('notification.try')

    toast.warn(
      toastMessage(localeTryNotification.replace('$action', localeAction))
    )

    try {
      await products.delete(id)

      context.deleteProduct(id)

      const localeSuccessNotification = locale.getTranslation(
        'notification.success'
      )

      toast.success(
        toastMessage(localeSuccessNotification.replace('$action', localeAction))
      )

      setOpen(false)
    } catch (exception) {
      errors.handleForException(exception)
    }
  }

  return (
    <DeleteEntityModal
      handleSubmit={handleSubmit}
      open={open}
      setOpen={setOpen}
      entity={locale.getTranslation('entity.product')}
      id={id}
    />
  )
}

export default ProductDeleteModal
