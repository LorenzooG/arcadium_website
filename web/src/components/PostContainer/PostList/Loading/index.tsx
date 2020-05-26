import React from "react";

import PostLoading from "~/components/PostContainer/PostComponent/Loading";

import { Container } from "../styles";

const PostsLoading: React.FC = () => {
  return (
    <Container>
      <ul>
        {[1, 2, 3, 4, 5].map(key => (
          <PostLoading defaultHeight key={key} />
        ))}
      </ul>
    </Container>
  );
};

export default PostsLoading;
