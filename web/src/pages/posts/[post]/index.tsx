import React from 'react'

import useSwr from 'swr'

import { NextPage } from 'next'

import { Comment, Post } from '~/services/entities'
import { commentService, postService } from '~/services/crud'

import { Container } from './styles'
import { PostItem } from '~/components/PostContainer'
import { wrapper } from '~/store'
import { CommentList } from '~/components/CommentContainer'

interface Props {
  postId: string
  post?: Post
  comments?: Comment[]
}

const PostPage: NextPage<Props> = ({ postId, post, comments }) => {
  const { data, error } = useSwr(
    () => postId,
    id => postService.findOne(id),
    {
      initialData: post,
    }
  )

  if (error) {
    return <div>Error: {JSON.stringify(error)}</div>
  }

  if (!data) {
    return <div>Loading post...</div>
  }

  return (
    <Container>
      <PostItem post={data} />
      <CommentList post={data} initialData={comments} />
    </Container>
  )
}

PostPage.getInitialProps = async ({ req, query }) => {
  const postId = Array.isArray(query.post) ? query.post[0] : query.post

  if (!req) return { postId }

  const { data: comments } = await commentService.findAllOfPost(postId)

  return {
    postId,
    post: await postService.findOne(postId),
    comments,
  }
}

export default wrapper.withRedux(PostPage)
