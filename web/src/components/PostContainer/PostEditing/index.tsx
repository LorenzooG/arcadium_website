import React from 'react'

import { Post } from '~/services/entities'

import { Container } from './styles'

interface Props {
  post: Post
}

export const PostEditing: React.FC<Props> = () => {
  return <Container />
}
