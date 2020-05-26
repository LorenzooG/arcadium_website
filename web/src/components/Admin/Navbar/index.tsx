import React from "react";

import { FiMenu } from "react-icons/fi";

import { Link } from "react-router-dom";

import { locale } from "~/services";

import { Container, Item, Toggler, ReturnToTheSite } from "./styles";

type Props = {
  path?: string;
  toggleSidebar(): void;
  sidebarOpen: boolean;
};

const AdminNavbar: React.FC<Props> = ({
  path = locale.getTranslation("page.home"),
  toggleSidebar,
  sidebarOpen
}) => {
  return (
    <Container sidebarOpen={sidebarOpen}>
      <Toggler onClick={toggleSidebar}>
        <FiMenu />
      </Toggler>

      <Item>
        <h3>{path.toUpperCase()}</h3>
      </Item>

      <ReturnToTheSite>
        <Link to={"/"}>
          {locale.getTranslation("action.return.to.the.site").toUpperCase()}
        </Link>
      </ReturnToTheSite>
    </Container>
  );
};

export default AdminNavbar;
