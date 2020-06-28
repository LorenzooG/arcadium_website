import React, { useCallback, useEffect, useState } from 'react'

import useSwr from 'swr'

import { NextPage } from 'next'

import { Comment, Post } from '~/services/entities'
import { commentService, postService } from '~/services/crud'

import { Container } from './styles'
import { PostItem } from '~/components/PostContainer'
import { wrapper } from '~/store'
import { CommentList } from '~/components/CommentContainer'
import { useFetcher } from '~/utils'

interface Props {
  postId: string
  initialData?: Post
  comments?: Comment[]
}

const PostPage: NextPage<Props> = ({ postId, initialData, comments }) => {
  const { data, error, loading } = useFetcher({
    fetcher: () => postService.findOne(postId),
    initialData,
  })

  if (error) {
    return <div>Error: {JSON.stringify(error)}</div>
  }

  if (loading || !data) {
    return <div>Loading post...</div>
  }

  console.log('Post:', data)

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
    initialData: await postService.findOne(postId),
    comments,
  }
}

export default wrapper.withRedux(PostPage)
