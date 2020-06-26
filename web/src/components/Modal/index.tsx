import React, { useEffect } from 'react'

import { FiXCircle } from 'react-icons/fi'

import {
  BackButton,
  Container,
  Content,
  Footer,
  Header,
  Main,
  SubmitButton
} from './styles'

type Props = {
  open: boolean

  title?: string

  submit?: string

  handleBack?: () => void

  handleSubmit?: () => void

  setOpen: (value: boolean) => void
}

const Modal: React.FC<Props> = ({
  open,
  submit,
  title = 'Modal',
  setOpen,
  handleSubmit,
  handleBack = () => setOpen(false),
  children
}) => {
  useEffect(() => {
    if (open) {
      document.body.style.overflow = 'hidden'
    }

    return () => {
      document.body.style.overflow = 'auto'
    }
  }, [open])

  if (!open) {
    return null
  }

  return (
    <Container>
      <Main>
        <Header>
          <h1>{title}</h1>

          <BackButton onClick={handleBack}>
            <FiXCircle />
          </BackButton>
        </Header>

        <Content>{children}</Content>

        <Footer>
          {submit && (
            <SubmitButton onClick={handleSubmit}>
              {submit.toUpperCase()}
            </SubmitButton>
          )}
        </Footer>
      </Main>
    </Container>
  )
}

export default Modal
