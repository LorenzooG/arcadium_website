import React from 'react'

import { requestPlayerHead, toLocalePrice } from '~/utils'

import { Modal } from '~/components'

import { locale } from '~/services'
import { Payment } from '~/services/entities'

import { Container, Field, SubField, SubProduct } from './styles'

type Props = {
  open: boolean
  setOpen: (value: boolean) => void
  payment: Payment
}

const AdminPaymentEditModal: React.FC<Props> = ({ open, setOpen, payment }) => {
  // noinspection SuspiciousTypeOfGuard
  return (
    <Modal
      open={open}
      setOpen={setOpen}
      title={`${locale.getTranslation('entity.payment')} ${payment.id}`}
    >
      <Container>
        <h1>
          {locale.getTranslation('entity.payment')}: {payment.id}
        </h1>
        <Field>
          {locale.getTranslation('message.user_name')}:
          <span>{payment.userName}</span>
          <img
            src={requestPlayerHead(payment.userName)}
            alt={payment.userName}
          />
        </Field>
        <Field>
          {locale.getTranslation('entity.user')}:
          <span>
            {payment.user.email} | {payment.user.id}
          </span>
          <img
            src={requestPlayerHead(payment.user.name)}
            alt={payment.user.email}
          />
        </Field>
        <Field>
          {locale.getTranslation('message.delivered')}:
          <span>
            {payment.isPaid
              ? locale.getTranslation('message.yes')
              : locale.getTranslation('message.no')}
          </span>
        </Field>
        <Field>
          {locale.getTranslation('message.is.paid')}:{' '}
          <span>
            {payment.isPaid
              ? locale.getTranslation('message.yes')
              : locale.getTranslation('message.no')}
          </span>
        </Field>
        <Field>
          {locale.getTranslation('message.origin.ip')}:{' '}
          <span>{payment.originIp}</span>
        </Field>
        <Field>
          {locale.getTranslation('message.payment.method')}:{' '}
          <span>{payment.paymentMethod}</span>
        </Field>
        <Field>
          {locale.getTranslation('message.total.price')}:{' '}
          <span>{toLocalePrice(payment.totalPrice())}</span>
        </Field>

        <Field>
          {locale.getTranslation('message.updated.at')}:{' '}
          <span>{payment.updatedAt.toLocaleString()}</span>
        </Field>

        <Field>{locale.getTranslation('message.products')}: </Field>
        {payment.products.map((product, index) =>
          typeof product.product === 'number' ? (
            String(product.product)
          ) : (
            <SubProduct key={index}>
              <SubField>
                {locale.getTranslation('message.amount')}:{' '}
                <span>{product.amount}</span>
              </SubField>
              <SubField>
                {locale.getTranslation('entity.product')}:{' '}
                <span>{product.product.id}</span>
              </SubField>
            </SubProduct>
          )
        )}

        <Field>
          {locale.getTranslation('message.created.at')}:{' '}
          <span>{payment.createdAt.toLocaleString()}</span>
        </Field>
      </Container>
    </Modal>
  )
}

export default AdminPaymentEditModal
