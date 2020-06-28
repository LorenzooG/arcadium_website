import React, { useEffect, useState } from 'react'

import Link from 'next/link'

import { FiStar, FiEdit } from 'react-icons/fi'

import { postService } from '~/services/crud'
import { Post } from '~/services/entities'

import { PostContent } from '..'

import {
  Container,
  Header,
  Content,
  UserAvatar,
  StarIcon,
  EditIcon,
} from './styles'

interface Props {
  post: Post
}

export const PostItem: React.FC<Props> = ({ post }) => {
  const [loaded, setLoaded] = useState(false)
  const [liked, setLiked] = useState(false)
  const [isOwner, setOwner] = useState(false)

  useEffect(() => {
    async function fetchHasLiked() {
      setLiked(await postService.hasLiked(post.id))
    }

    fetchHasLiked().then(() => setLoaded(true))
  }, [post.id])

  useEffect(() => {
    setOwner(postService.isOwner(post.id))
  }, [post.id])

  async function handleLikeOrUnlikePost() {
    if (!loaded) return

    if (liked) {
      await postService.unlike(post.id)
    } else {
      await postService.like(post.id)
    }
  }

  function handleStartEditing() {
    //
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
          <FiStar size={27} fill={liked ? '#fff' : 'transparent'} />
        </StarIcon>

        {post.isComplete && isOwner && (
          <EditIcon onClick={handleStartEditing}>
            <FiEdit size={27} />
          </EditIcon>
        )}
      </Header>

      <Content>
        <PostContent post={post} />
      </Content>
    </Container>
  )
}
