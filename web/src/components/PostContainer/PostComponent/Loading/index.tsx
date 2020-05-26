import React from "react";

import {
  Container as PostContainer,
  Bar,
  Header,
  Content
} from "./styles";
import { RandomLoading } from "~/styles";

type Props = {
  defaultHeight?: boolean;
};

const PostLoading: React.FC<Props> = ({ defaultHeight = false }) => {
  return (
    <PostContainer>
      <Header>
        <Bar>
          <RandomLoading />
        </Bar>
      </Header>
      <Content defaultHeight={defaultHeight}>
        <Bar>
          <RandomLoading />
        </Bar>
      </Content>
    </PostContainer>
  );
};

export default PostLoading;
