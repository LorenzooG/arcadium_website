import React from 'react'

import { NextPage } from 'next'

import { useSelector } from 'react-redux'

import { END } from '@redux-saga/core'

import { SagaStore, wrapper } from '~/store'
import { actionFetchPosts } from '~/store/modules/posts/actions'
import { PostList } from '~/components/PostContainer'

const Index: NextPage = () => {
  const { posts, error, loading } = useSelector<any, any>(state => state.posts)

  if (loading) {
    return <>Loading</>
  }

  if (error) {
    return <>Error</>
  }

  return <PostList posts={posts} />
}

Index.getInitialProps = async ({ query, store: _store }) => {
  const store = _store as SagaStore

  const page = Array.isArray(query.page) ? query.page[0] : query.page

  store.dispatch(actionFetchPosts(parseInt(page)))
  store.dispatch(END)

  await store.sagaTask?.toPromise()
}

export default wrapper.withRedux(Index)
