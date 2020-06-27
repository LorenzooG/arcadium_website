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

Index.getInitialProps = async ({ store: _store }) => {
  const store = _store as SagaStore

  store.dispatch(actionFetchPosts())
  store.dispatch(END)

  await store.sagaTask?.toPromise()
}

export default wrapper.withRedux(Index)
