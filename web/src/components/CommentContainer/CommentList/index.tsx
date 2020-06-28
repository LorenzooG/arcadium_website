import React, { useEffect, useState } from 'react'
import { Post, Comment } from '~/services/entities'
import { CommentItem } from '~/components/CommentContainer'
import { commentService } from '~/services/crud'

interface Props {
  post: Post
}

export const CommentList: React.FC<Props> = ({ post }) => {
  const [comments, setComments] = useState<Comment[]>([])
  const [loaded, setLoaded] = useState(false)

  useEffect(() => {
    async function fetchPostComments() {
      console.log('POSTS FUCK')

      try {
        const { data } = await commentService.findAllOfPost(post.id)

        setComments(data)
      } catch {
        // do not show the error
      }
    }

    fetchPostComments().then(() => setLoaded(true))
  }, [post.id])

  return (
    <ul>
      <h1>Comments: </h1>
      {loaded ? (
        comments.map((comment, index) => (
          <CommentItem key={index} comment={comment} />
        ))
      ) : (
        <div>Loading</div>
      )}
    </ul>
  )
}
