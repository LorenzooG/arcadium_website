import React, { useEffect, useState } from 'react'
import { Post, Comment } from '~/services/entities'
import { CommentItem } from '~/components/CommentContainer'
import { commentService } from '~/services/crud'

import useSwr from 'swr'

interface Props {
  post: Post
  initialData?: Comment[]
}

const findPostCommentsFetcher = async (postId: unknown) => {
  const paginator = await commentService.findAllOfPost(postId)

  return paginator.data
}

export const CommentList: React.FC<Props> = ({ post, initialData }) => {
  const { data, error } = useSwr(
    () => post.id.toString(),
    findPostCommentsFetcher,
    { initialData }
  )

  return (
    <ul>
      <h1>Comments: </h1>
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
  )
}
