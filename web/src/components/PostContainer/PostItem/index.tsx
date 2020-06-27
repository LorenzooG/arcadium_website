import React, { useEffect, useState } from 'react'

import Link from 'next/link'

import { FiStar } from 'react-icons/fi'

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
import { getService, PostService } from '~/services/crud'

interface Props {
  post: Post
}

export const PostItem: React.FC<Props> = ({ post }) => {
  const [loaded, setLoaded] = useState(false)
  const [liked, setLiked] = useState(false)

  useEffect(() => {
    const postService = getService(PostService)

    async function fetchHasLiked() {
      setLiked(await postService.hasLiked(post.id))
    }

    fetchHasLiked().then(() => setLoaded(true))
  }, [post.id])

  async function handleLikeOrUnlikePost() {
    if (!loaded) return

    const postService = getService(PostService)

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
            <Link href={`profile/${post.createdBy.id}`}>
              <a>{post.createdBy.username}</a>
            </Link>
          </div>
          <span>{post.createdAt?.toDateString?.()}</span>
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
        <Fade>
          <Link href={`/posts/${post.id}`}>
            <a>Read more</a>
          </Link>
        </Fade>
      </Content>
    </Container>
  )
}
