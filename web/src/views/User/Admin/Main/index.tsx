import React, { useState } from "react";

import { Navbar, Sidebar } from "~/components/Admin";

import { Container, Page } from "./styles";

type Props = {
  path?: string;
};

const AdminWrapper: React.FC<Props> = ({ children, path }) => {
  const [open, setOpen] = useState(false);

  return (
    <Container>
      <div>
        <Sidebar open={open} />

        <Navbar
          sidebarOpen={open}
          path={path}
          toggleSidebar={() => setOpen(!open)}
        />
      </div>

      <Page>{children}</Page>
    </Container>
  );
};

export default AdminWrapper;
