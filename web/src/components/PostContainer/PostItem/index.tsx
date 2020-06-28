import React, { useEffect, useState } from 'react'

import Link from 'next/link'

import { FiStar } from 'react-icons/fi'

import { postService } from '~/services/crud'
import { Post } from '~/services/entities'

import {
  Container,
  Header,
  Content,
  ContentText,
  Fade,
  Title,
  UserAvatar,
  StarIcon,
} from './styles'

interface Props {
  post: Post
}

export const PostItem: React.FC<Props> = ({ post }) => {
  const [loaded, setLoaded] = useState(false)
  const [liked, setLiked] = useState(false)

  useEffect(() => {
    async function fetchHasLiked() {
      setLiked(await postService.hasLiked(post.id))
    }

    fetchHasLiked().then(() => setLoaded(true))
  }, [post.id])

  async function handleLikeOrUnlikePost() {
    if (!loaded) return

    if (liked) {
      await postService.unlike(post.id)
    } else {
      await postService.like(post.id)
    }
  }

  return (
    <Container>
      <Header>
        <UserAvatar src={post.createdBy.avatar} />

        <div className={'info'}>
          <div>
            <Link href={'/profile/[user]'} as={`profile/${post.createdBy.id}`}>
              <a>{post.createdBy.username}</a>
            </Link>
          </div>
          <span>{new Date(post.createdAt).toDateString()}</span>
        </div>

        <StarIcon onClick={handleLikeOrUnlikePost}>
          <FiStar fill={liked ? '#fff' : 'transparent'} />
        </StarIcon>
      </Header>

      <Content>
        <Title>{post.title}</Title>
        <ContentText
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
      </Content>
    </Container>
  )
}
