import React from 'react'

import Link from 'next/link'

import { Comment } from '~/services/entities'

import { Container, Header, UserAvatar, Content } from './styles'

interface Props {
  comment: Comment
}

export const CommentItem: React.FC<Props> = ({ comment }) => {
  return (
    <Container>
      <Header>
        <UserAvatar src={comment.createdBy.avatar} />

        <Link
          href={'/profiles/[user]'}
          as={`/profiles/${comment.createdBy.id}`}
        >
          <a>
            <h4>{comment.createdBy.username}</h4>
          </a>
        </Link>
      </Header>

      <Content>{comment.content}</Content>
    </Container>
  )
}
