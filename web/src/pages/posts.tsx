import React from 'react'

import { NextPage } from 'next'

import { END } from '@redux-saga/core'

import { SagaStore, useTypedSelector, wrapper } from '~/store'
import { actionFetchPosts } from '~/store/modules/posts/actions'
import { PostList } from '~/components/PostContainer'

const Posts: NextPage = () => {
  const { posts, error, loading } = useTypedSelector(state => state.posts)

  if (loading) {
    return <>Loading</>
  }

  if (error) {
    return <>Error</>
  }

  return <PostList posts={posts} />
}

Posts.getInitialProps = async ({ query, store: _store }) => {
  const store = _store as SagaStore

  const page = Array.isArray(query.page) ? query.page[0] : query.page

  store.dispatch(actionFetchPosts(parseInt(page)))
  store.dispatch(END)

  await store.sagaTask?.toPromise()
}

export default wrapper.withRedux(Posts)
