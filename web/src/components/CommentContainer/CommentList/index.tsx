import React from 'react'
import { Post, Comment } from '~/services/entities'
import { CommentItem } from '~/components/CommentContainer'
import { commentService } from '~/services/crud'

import { useFetcher } from '~/utils'

import { Container, Title } from './styles'

interface Props {
  post: Post
  initialData?: Comment[]
}

const findPostCommentsFetcher = async (postId: unknown) => {
  const paginator = await commentService.findAllOfPost(postId)

  return paginator.data
}

export const CommentList: React.FC<Props> = ({ post, initialData }) => {
  const { data, error } = useFetcher({
    fetcher: () => findPostCommentsFetcher(post.id),
    initialData,
  })

  return (
    <Container>
      <Title>Comments: </Title>
      <ul>
        {data ? (
          data.map((comment, index) => (
            <CommentItem key={index} comment={comment} />
          ))
        ) : error ? (
          <div>Error</div>
        ) : (
          <div>Loading</div>
        )}
      </ul>
    </Container>
  )
}
