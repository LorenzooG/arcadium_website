import React, { useState } from "react";

import { FiEye, FiFileText } from "react-icons/fi";

import { toLocalePrice } from "~/utils";

import PaymentViewModal from "../PaymentViewModal";

import { Payment } from "~/services/entities";

import { Container, EditButton, Icon } from "./styles";

type Props = {
  payment: Payment;
};

const AdminPaymentComponent: React.FC<Props> = ({ payment }) => {
  const [open, setOpen] = useState(false);

  return (
    <Container key={payment.id}>
      <PaymentViewModal open={open} payment={payment} setOpen={setOpen}/>

      <span>{payment.id}</span>

      <Icon>
        <FiFileText/>
      </Icon>

      <span>{payment.user.name}</span>

      <span>{toLocalePrice(payment.totalPrice())}</span>

      <EditButton onClick={() => setOpen(true)}>
        <FiEye/>
      </EditButton>
    </Container>
  );
};

export default AdminPaymentComponent;
