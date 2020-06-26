import React from 'react'
import { AppProps } from 'next/app'

import { wrapper } from '~/store'

const App: React.FC<AppProps> = ({ Component, pageProps }) => {
  return <Component {...pageProps} />
}

export default wrapper.withRedux(App)
