import React, { useEffect, useState } from "react";

import produce from "immer";
import { useResource } from "~/utils";

import Error from "~/components/ErrorComponent";
import Loading from "./PostList/Loading";
import PostList from "./PostList";

import { Post } from "~/services/entities";
import { posts as service } from "~/services";

import ContainerContext from "./PostContainerContext";

const AdminPostContainer: React.FC = () => {
  const [_posts, loading, error] = useResource<Post[]>(service.fetchAll);
  const [posts, setPosts] = useState(_posts);

  useEffect(() => void setPosts(_posts), [_posts]);

  if (loading) {
    return <Loading/>;
  }

  if (error) {
    return <Error/>;
  }

  return (
    <ContainerContext.Provider
      value={{
        addPost: post => {
          setPosts(
            produce(posts, draft => {
              draft.push(post);
            })
          );
        },
        deletePost: id => {
          setPosts(
            produce(posts, draft => {
              draft.splice(
                draft.findIndex(post => post.id === id),
                1
              );
            })
          );
        },
        updatePost: (id, post) => {
          setPosts(
            produce(posts, draft => {
              draft[draft.findIndex(_post => _post.id === id)] = post;
            })
          );
        }
      }}
    >
      <PostList posts={posts}/>
    </ContainerContext.Provider>
  );
};

export default AdminPostContainer;
