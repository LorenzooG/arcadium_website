import React from 'react'
import Document, { Head, Html, NextScript, Main } from 'next/document'

class AppDocument extends Document {
  render() {
    return (
      <Html>
        <Head>
          <title>App</title>
        </Head>
        <body>
          <Main />
          <NextScript />
        </body>
      </Html>
    )
  }
}

export default AppDocument
