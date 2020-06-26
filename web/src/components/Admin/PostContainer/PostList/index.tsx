import React, { useState } from "react";

import { FiPlusCircle } from "react-icons/fi";

import PostComponent from "../PostComponent";
import PostEditModal from "../PostEditModal";

import { Post } from "~/services/entities";

import { AddPostButton, Container, List } from "./styles";

type Props = {
  posts: Post[];
};

const AdminUserList: React.FC<Props> = ({ posts }) => {
  const [open, setOpen] = useState(false);

  return (
    <Container>
      <PostEditModal create open={open} setOpen={setOpen}/>

      <List>
        {posts.map(post => (
          <PostComponent post={post} key={post.id}/>
        ))}

        <li>
          <AddPostButton onClick={() => setOpen(true)}>
            <FiPlusCircle/>
          </AddPostButton>
        </li>
      </List>
    </Container>
  );
};

export default AdminUserList;
