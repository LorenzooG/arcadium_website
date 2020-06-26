import React from "react";

import { useResource } from "~/utils";

import { UserWrapper } from "~/components";
import AdminWrapper from "../Main";

import {
  locale,
  payments as paymentService,
  posts as postService,
  products as productService,
  users as userService
} from "~/services";
import { Payment, Post, Product, User } from "~/services/entities";

import { FakeChart, Item, List } from "./styles";

const AdminHome: React.FC = () => {
  const [payments] = useResource<Payment[]>(paymentService.fetchAll);
  const [users] = useResource<User[]>(userService.fetchAll);
  const [posts] = useResource<Post[]>(postService.fetchAll);
  const [products] = useResource<Product[]>(productService.fetchAll);

  return (
    <AdminWrapper path={locale.getTranslation("page.admin")}>
      <UserWrapper>
        <header>
          <h1>{locale.getTranslation("page.admin")}</h1>
        </header>
        <div>
          <List>
            <Item>
              <h2>
                {payments?.length ?? 0} {locale.getTranslation("page.payments")}
              </h2>
            </Item>

            <Item>
              <h2>
                {users?.length ?? 0} {locale.getTranslation("page.users")}
              </h2>
            </Item>

            <Item>
              <h2>
                {products?.length ?? 0} {locale.getTranslation("page.products")}
              </h2>
            </Item>

            <Item>
              <h2>
                {posts?.length ?? 0} {locale.getTranslation("page.posts")}
              </h2>
            </Item>
          </List>

          <FakeChart>
            <h1>
              {locale
                .getTranslation("message.coming.soon.thing")
                .replace("$thing", locale.getTranslation("message.chart"))}
            </h1>
          </FakeChart>
        </div>
      </UserWrapper>
    </AdminWrapper>
  );
};

export default AdminHome;
