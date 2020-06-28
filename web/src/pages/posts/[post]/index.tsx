import React from 'react'

import useSwr from 'swr'

import { GetStaticProps, NextPage } from 'next'

import { Post } from '~/services/entities'
import { postService } from '~/services/crud'

import { PostsState } from '~/store/modules/posts/reducer'

import { Container } from './styles'
import { PostItem } from '~/components/PostContainer'
import { wrapper } from '~/store'
import { CommentList } from '~/components/CommentContainer'

interface Props {
  postId: string
  initialData?: Post
}

const PostPage: NextPage<Props> = ({ postId, initialData }) => {
  const { data, error } = useSwr(
    () => postId,
    id => postService.findOne(id),
    {
      initialData,
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
      <CommentList post={data} />
    </Container>
  )
}

PostPage.getInitialProps = async ({ req, query }) => {
  const postId = Array.isArray(query.post) ? query.post[0] : query.post

  if (!req) return { postId }

  return {
    postId,
    initialData: await postService.findOne(postId),
  }
}

export default wrapper.withRedux(PostPage)
