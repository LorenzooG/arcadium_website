import React from 'react'

import { Post } from '~/services/entities'

import { Container } from './styles'

interface Props {
  post: Post
}

export const PostItem: React.FC<Props> = ({ post }) => {
  return (
    <Container>
      <div>{post.title}</div>
    </Container>
  )
}
