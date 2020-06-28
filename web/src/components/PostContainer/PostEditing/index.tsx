import React, { useRef } from 'react'

import { Post } from '~/services/entities'

import {
  Container,
  Form,
  Input,
  Submit,
  Label,
  InputGroup,
  TextBox,
} from './styles'

interface Props {
  post: Post
}

export const PostEditing: React.FC<Props> = ({ post }) => {
  const titleRef = useRef<HTMLInputElement>(null)
  const descriptionRef = useRef<HTMLTextAreaElement>(null)

  const handleOnSubmit = (event: React.FormEvent) => {
    event.preventDefault()
  }

  return (
    <Container>
      <Form onSubmit={handleOnSubmit}>
        <InputGroup>
          <Label htmlFor={'title'}>Title</Label>
          <Input name={'title'} value={post.title} ref={titleRef} />
        </InputGroup>

        <InputGroup>
          <Label htmlFor={'title'}>Description</Label>
          <TextBox value={post.description} ref={descriptionRef} />
        </InputGroup>

        <Submit>FINISH</Submit>
      </Form>
    </Container>
  )
}
