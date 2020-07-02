import React from 'react'

import Link from 'next/link'

import { Post } from '~/services/entities'

import { Content, Fade, Title } from './styles'

interface Props {
  post: Post
}

export const PostContent: React.FC<Props> = ({ post }) => {
  return (
    <>
      <Title>{post.title}</Title>
      <Content
        dangerouslySetInnerHTML={{
          __html: post.description,
        }}
      />
      {!post.isComplete && (
        <Fade>
          <Link href={'/posts/[post]'} as={`/posts/${post.id}`}>
            <a>Read more</a>
          </Link>
        </Fade>
      )}
    </>
  )
}
