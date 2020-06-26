import React from "react";

import PaymentComponent from "../PaymentComponent";

import { Payment } from "~/services/entities";

import { Container, List } from "./styles";

type Props = {
  payments: Payment[];
};

const AdminPaymentList: React.FC<Props> = ({ payments }) => {
  return (
    <Container>
      <List>
        {payments.map(payment => (
          <PaymentComponent payment={payment} key={payment.id}/>
        ))}
      </List>
    </Container>
  );
};

export default AdminPaymentList;
