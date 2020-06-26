import React from "react";

import Error from "~/components/ErrorComponent";
import Loading from "./PaymentList/Loading";
import PaymentList from "./PaymentList";

import { useResource } from "~/utils";

import { payments as service } from "~/services";
import { Payment } from "~/services/entities";

const AdminProductContainer: React.FC = () => {
  const [payments, loading, error] = useResource<Payment[]>(service.fetchAll);

  if (loading) {
    return <Loading/>;
  }

  if (error) {
    return <Error/>;
  }

  return <PaymentList payments={payments}/>;
};

export default AdminProductContainer;
