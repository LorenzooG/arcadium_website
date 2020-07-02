import React from 'react'

import { Post } from '~/services/entities'

import { PostItem } from '~/components/PostContainer'

import { Container } from './styles'

interface Props {
  posts: Post[]
}

export const PostList: React.FC<Props> = ({ posts }) => {
  return (
    <Container>
      {posts.map((post, index) => (
        <PostItem key={index} post={post} />
      ))}
    </Container>
  )
}
