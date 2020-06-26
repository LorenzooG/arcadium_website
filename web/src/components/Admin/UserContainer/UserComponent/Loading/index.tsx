import React from "react";

import { Container } from "./styles";
import { Bar, Loading } from "~/styles";

const AdminUserComponentLoading: React.FC = () => {
  return (
    <Container>
      <Bar size={"64px"}>
        <Loading/>
      </Bar>
    </Container>
  );
};

export default AdminUserComponentLoading;
