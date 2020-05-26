import React from "react";

import PostComponent from "../PostComponent";
import Empty from "./Empty";

import { Post } from "~/services/entities";

import { Container } from "./styles";

type Props = {
  posts: Post[];
};

const PostList: React.FC<Props> = ({ posts }) => {
  return (
    <Container>
      <ul>
        {posts.length > 0 ? (
          posts.map(post => <PostComponent key={post.id} post={post} />)
        ) : (
          <Empty />
        )}
      </ul>
    </Container>
  );
};

export default PostList;
