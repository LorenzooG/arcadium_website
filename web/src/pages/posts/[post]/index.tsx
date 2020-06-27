import React from 'react'

import { NextPage } from 'next'

import { Post } from '~/services/entities'
import { postService } from '~/services/crud'

import { PostsState } from '~/store/modules/posts/reducer'

import { Container } from './styles'
import { PostItem } from '~/components/PostContainer'
import { wrapper } from '~/store'

interface Props {
  post: Post
}

const PostPage: NextPage<Props> = ({ post }) => {
  return (
    <Container>
      <PostItem post={post} />
    </Container>
  )
}

PostPage.getInitialProps = async ({ query, store }) => {
  const postIdString = Array.isArray(query.post) ? query.post[0] : query.post
  const postId = parseInt(postIdString)

  const state = store.getState()
  const posts: PostsState = state.posts
  const post = posts.posts.find(post => post.id === postId)

  if (!post || !post?.isComplete) {
    return { post: await postService.findOne(postId) }
  }

  return { post }
}

export default wrapper.withRedux(PostPage)
