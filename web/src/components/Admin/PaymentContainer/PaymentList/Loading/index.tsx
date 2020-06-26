import React from "react";

import { Container, List } from "../styles";

import PaymentComponentLoading from "~/components/Admin/ProductContainer/ProductComponent/Loading";

const AdminPaymentListLoading: React.FC = () => {
  return (
    <Container>
      <List>
        {[1, 2, 3, 4, 5].map(key => (
          <PaymentComponentLoading key={key}/>
        ))}
      </List>
    </Container>
  );
};

export default AdminPaymentListLoading;
