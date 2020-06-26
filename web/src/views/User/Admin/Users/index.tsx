import React from "react";

import AdminWrapper from "../Main";
import { UserWrapper } from "~/components";
import { UserContainer } from "~/components/Admin";

import { locale } from "~/services";

const AdminUsers: React.FC = () => {
  return (
    <AdminWrapper path={locale.getTranslation("page.users")}>
      <UserWrapper>
        <header>
          <h1>{locale.getTranslation("page.users")}</h1>
        </header>

        <UserContainer/>
      </UserWrapper>
    </AdminWrapper>
  );
};

export default AdminUsers;
