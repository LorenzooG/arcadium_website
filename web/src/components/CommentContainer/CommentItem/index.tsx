import React from 'react'

import { Comment } from '~/services/entities'

interface Props {
  comment: Comment
}

export const CommentItem: React.FC<Props> = ({ comment }) => {
  return (
    <div>
      <h1>{comment.content}</h1>
    </div>
  )
}
