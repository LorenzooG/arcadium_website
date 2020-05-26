import React from "react";

import { useSelector } from "react-redux";
import { RootState } from "~/store/modules";
import { Item } from "~/store/modules/cart/reducer";

import CartList from "./CartList";
import CartCheckout from "./CartCheckout";

import { Container } from "./styles";

const CartContainer: React.FC = () => {
  const items = useSelector<RootState, Item[]>(state => state.cart.items);

  return (
    <Container>
      <CartList items={items} />
      <CartCheckout items={items} />
    </Container>
  );
};

export default CartContainer;
