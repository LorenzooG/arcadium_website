import { createContext } from "react";

import { Post } from "~/services/entities";

type ContextType = {
  addPost: (post: Post) => void;
  deletePost: (id: number) => void;
  updatePost: (id: number, post: Post) => void;
};

const PostContainerContext = createContext<ContextType>({
  addPost: () => {
    return;
  },
  updatePost: () => {
    return;
  },
  deletePost: () => {
    return;
  }
});

export default PostContainerContext;
