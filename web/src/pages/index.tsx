import React from 'react'

import { NextPage } from 'next'

import { useSelector } from 'react-redux'

import { END } from '@redux-saga/core'

import { SagaStore, wrapper } from '~/store'
import { actionFetchPosts } from '~/store/modules/posts/actions'

const Index: NextPage = () => {
  const fuck = useSelector<any>(state => state.posts)

  return <>{JSON.stringify(fuck, null, 2)}</>
}

Index.getInitialProps = async ({ store: _store }) => {
  const store = _store as SagaStore

  store.dispatch(actionFetchPosts())
  store.dispatch(END)

  await store.sagaTask?.toPromise()
}

export default wrapper.withRedux(Index)
