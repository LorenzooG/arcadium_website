import React from "react";

import { Link } from "react-router-dom";

import { useSelector } from "react-redux";
import { RootState } from "~/store/modules";

import { requestPlayerHead } from "~/utils";

import { locale } from "~/services";
import { User } from "~/services/entities";

import { Container, Item, List } from "./styles";

type Props = {
  open: boolean;
};

const NAV_LINKS = [
  {
    title: locale.getTranslation("page.home"),
    link: "/user"
  },
  {
    title: locale.getTranslation("page.admin"),
    link: "/admin/"
  },
  {
    title: locale.getTranslation("page.posts"),
    link: "/admin/posts"
  },
  {
    title: locale.getTranslation("page.products"),
    link: "/admin/products"
  },
  {
    title: locale.getTranslation("page.users"),
    link: "/admin/users"
  },
  {
    title: locale.getTranslation("page.payments"),
    link: "/admin/payments"
  }
];

const NAV_USER_LINKS = [
  {
    title: locale.getTranslation("page.home"),
    link: "/user"
  }
];

const AdminSidebar: React.FC<Props> = ({ open }) => {
  const user = useSelector<RootState, User | null>(state => state.auth.account);

  const links = user?.isAdmin ? NAV_LINKS : NAV_USER_LINKS;

  return (
    user && (
      <Container open={open}>
        <div>
          <img alt={user.userName} src={requestPlayerHead(user.userName)} />
          <span>{user.name.toUpperCase()}</span>
        </div>

        <span>{locale.getTranslation("message.links").toUpperCase()}</span>

        <List>
          {links.map((link, index) => (
            <Item key={index}>
              <Link to={link.link}>{link.title}</Link>
            </Item>
          ))}
        </List>
      </Container>
    )
  );
};

export default AdminSidebar;
