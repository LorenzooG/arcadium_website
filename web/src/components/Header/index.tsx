import React from "react";

import { FiShoppingCart } from "react-icons/fi";

import { Link } from "react-router-dom";

import { useSelector } from "react-redux";
import { RootState } from "~/store/modules";
import { Item } from "~/store/modules/cart/reducer";

import { Navbar } from "~/components";

import { app, locale } from "~/services";

import {
  Wrapper,
  Container,
  CartIcon,
  CartWrapper,
  CartText,
  Title
} from "./styles";

const Header: React.FC = () => {
  const items = useSelector<RootState, Item[]>(state => state.cart.items);

  return (
    <>
      <Wrapper>
        <Container>
          <Title>
            <h1>{app.name()}</h1>
          </Title>
          <CartWrapper>
            <Link to={"/cart"}>
              <CartText>
                <h4>
                  {locale
                    .getTranslation("message.items.in.cart")
                    .replace("$amount", items.length.toString())}
                </h4>
              </CartText>
              <CartIcon>
                <FiShoppingCart />
              </CartIcon>
            </Link>
          </CartWrapper>
        </Container>
      </Wrapper>
      <Navbar />
    </>
  );
};

export default Header;
