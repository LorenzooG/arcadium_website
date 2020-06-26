import React from "react";

import AdminWrapper from "../Main";
import { UserWrapper } from "~/components";
import { PaymentContainer } from "~/components/Admin";

import { locale } from "~/services";

const AdminPayments: React.FC = () => {
  return (
    <AdminWrapper path={locale.getTranslation("page.payments")}>
      <UserWrapper>
        <header>
          <h1>{locale.getTranslation("page.payments")}</h1>
        </header>

        <PaymentContainer/>
      </UserWrapper>
    </AdminWrapper>
  );
};

export default AdminPayments;
