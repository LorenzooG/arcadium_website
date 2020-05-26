import React from "react";

import { Container } from "./styles";
import { Bar, Loading } from "~/styles";

const AdminPaymentComponentLoading: React.FC = () => {
  return (
    <Container>
      <Bar size={"64px"}>
        <Loading />
      </Bar>
    </Container>
  );
};

export default AdminPaymentComponentLoading;
