import React from "react";

import { useParams } from "react-router";

import { useResource } from "~/utils";

import PostComponent from "~/components/PostContainer/PostComponent";
import PostLoading from "~/components/PostContainer/PostComponent/Loading";
import { ErrorComponent } from "~/components";

import { Post } from "~/services/entities";
import { posts as service } from "~/services";

type Params = {
  post: string;
};

const HomePost: React.FC = () => {
  const params = useParams<Params>();
  const postId = parseFloat(params.post);

  const [post, loading, error] = useResource<Post>(service.fetch, postId);

  if (loading) {
    return <PostLoading />;
  }

  if (error) {
    return <ErrorComponent />;
  }

  return <PostComponent complete post={post} />;
};

export default HomePost;
