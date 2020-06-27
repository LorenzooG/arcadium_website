import React from 'react'

import Link from 'next/link'

import { FiUser } from 'react-icons/fi'

import { Post } from '~/services/entities'

import {
  Container,
  Header,
  Content,
  ContentText,
  Fade,
  Title,
  UserAvatar,
} from './styles'

interface Props {
  post: Post
}

export const PostItem: React.FC<Props> = ({ post }) => {
  if (!post) return <h3>ERROR FUCKING BITCH</h3>

  return (
    <Container>
      <Header>
        <UserAvatar src={post.createdBy.avatar} />

        <div className={'info'}>
          <div>
            <Link href={`profile/${post.createdBy.id}`}>
              <a>{post.createdBy.username}</a>
            </Link>
          </div>
          <span>{post.createdAt?.toDateString?.()}</span>
        </div>
      </Header>

      <Content>
        <Title>{post.title}</Title>
        <ContentText
          dangerouslySetInnerHTML={{
            __html: post.description,
          }}
        />
        <Fade>
          <Link href={`/posts/${post.id}`}>
            <a>Read more</a>
          </Link>
        </Fade>
      </Content>
    </Container>
  )
}
