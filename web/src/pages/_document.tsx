import React from 'react'
import Document, {
  Head,
  Html,
  NextScript,
  Main,
  DocumentContext,
} from 'next/document'
import { ServerStyleSheet } from 'styled-components'

interface Props {
  stylesTag: React.Component
}

class AppDocument extends Document<Props> {
  public static async getInitialProps({ renderPage }: DocumentContext) {
    const sheet = new ServerStyleSheet()

    const props = renderPage(App => props =>
      sheet.collectStyles(<App {...props} />)
    )

    const stylesTag = sheet.getStyleElement()

    return { ...props, stylesTag }
  }

  render() {
    return (
      <Html>
        <Head>
          <title>App</title>
          {this.props.stylesTag}
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
