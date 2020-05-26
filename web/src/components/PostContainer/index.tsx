import React, { useEffect } from "react";

import { useDispatch, useSelector } from "react-redux";
import { RootState } from "~/store/modules";
import { fetchPostsRequestAction } from "~/store/modules/posts/actions";

import { ErrorComponent } from "~/components";
import PostList from "./PostList";
import Loading from "./PostList/Loading";

import { Post } from "~/services/entities";

type Redux = {
  posts: Post[];
  loading: boolean;
  error: boolean;
};

const PostContainer: React.FC = () => {
  const { posts, loading, error } = useSelector<RootState, Redux>(
    state => state.posts
  );

  const dispatch = useDispatch();

  useEffect(() => {
    if (loading) {
      dispatch(fetchPostsRequestAction());
    }
  }, [loading, dispatch]);

  if (error) {
    return <ErrorComponent />;
  }

  if (loading) {
    return <Loading />;
  }

  return <PostList posts={posts} />;
};

export default PostContainer;
