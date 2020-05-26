import React from "react";

import { Link } from "react-router-dom";

import { useDispatch } from "react-redux";
import { Item } from "~/store/modules/cart/reducer";
import { clearCartAction } from "~/store/modules/cart/actions";

import CartItem from "../CartItem";

import { locale } from "~/services";

import { Container, Title, ClearCartButton, EmptyCart } from "./styles";

type Props = {
  items: Item[];
};

const CartList: React.FC<Props> = ({ items }) => {
  const dispatch = useDispatch();

  return (
    <div>
      <Container>
        <Title>
          <span>{locale.getTranslation("message.cart")}</span>
          <ClearCartButton onClick={() => dispatch(clearCartAction())}>
            {locale.getTranslation("message.clear.cart").toUpperCase()}
          </ClearCartButton>
        </Title>
        {items.length > 0 ? (
          <ul>
            {items.map(({ product, amount }) => (
              <CartItem key={product.id} product={product} amount={amount} />
            ))}
          </ul>
        ) : (
          <EmptyCart>
            <div>
              <h1>
                {locale.getTranslation("message.your.cart.is.empty")}{" "}
                <Link to={"/products"}>
                  {locale.getTranslation("message.your.cart.is.empty.link")}
                </Link>
              </h1>
            </div>
          </EmptyCart>
        )}
      </Container>
    </div>
  );
};

export default CartList;
