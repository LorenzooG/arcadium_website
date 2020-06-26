import React from "react";

import AdminWrapper from "../Main";
import { UserWrapper } from "~/components";
import { ProductContainer } from "~/components/Admin";

import { locale } from "~/services";

const AdminProducts: React.FC = () => {
  return (
    <AdminWrapper path={"Products"}>
      <UserWrapper>
        <header>
          <h1>{locale.getTranslation("page.products")}</h1>
        </header>

        <ProductContainer/>
      </UserWrapper>
    </AdminWrapper>
  );
};

export default AdminProducts;
