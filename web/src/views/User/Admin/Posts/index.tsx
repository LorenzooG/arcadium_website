import React from "react";

import AdminWrapper from "../Main";
import { UserWrapper } from "~/components";
import { PostContainer } from "~/components/Admin";

import { locale } from "~/services";

const AdminPosts: React.FC = () => {
  return (
    <AdminWrapper path={locale.getTranslation("page.posts")}>
      <UserWrapper>
        <header>
          <h1>{locale.getTranslation("page.posts")}</h1>
        </header>

        <PostContainer />
      </UserWrapper>
    </AdminWrapper>
  );
};

export default AdminPosts;
