import React, { useEffect, useState } from 'react'

import { FiUser } from 'react-icons/fi'

import { Link } from 'react-router-dom'

import { locale, markdown } from '~/services'
import { Post } from '~/services/entities'

import { Container, Content, ContentText, Fade, Header, Title } from './styles'

type Props = {
  post: Post
  complete?: boolean
}

const PostComponent: React.FC<Props> = ({ post, complete = false }) => {
  const [description, setDescription] = useState(post.description)

  useEffect(() => {
    if (!complete) {
      setDescription(
        post.description
          .replace(post.description.substring(1000), '')
          .concat(post.description.length > 1000 ? '...' : '')
      )
    }
  }, [post.description, complete])

  return (
    <Container>
      <Header>
        <FiUser />
        <div className={'info'}>
          <div>{locale.getTranslation('message.administration')}</div>
          <span>{post.createdAt.toLocaleString()}</span>
        </div>
      </Header>

      <Content>
        <Title>{post.title}</Title>
        <ContentText
          dangerouslySetInnerHTML={{
            __html: markdown.render(description)
          }}
        />
        {description.length > 1000 && !complete && (
          <Fade>
            <Link to={`/posts/${post.id}`}>Read more</Link>
          </Fade>
        )}
      </Content>
    </Container>
  )
}

export default PostComponent
