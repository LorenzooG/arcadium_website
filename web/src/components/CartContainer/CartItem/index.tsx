import React from "react";

import { FiTrash } from "react-icons/fi";

import { useDispatch } from "react-redux";
import {
  removeFromCartAction,
  updateCartAction
} from "~/store/modules/cart/actions";

import { toLocalePrice } from "~/utils";

import { Product } from "~/services/entities";

import { Container, AmountInput, ProductImage, RemoveButton } from "./styles";

type Props = {
  product: Product;
  amount: number;
};

const CartItem: React.FC<Props> = ({ product, amount }) => {
  const dispatch = useDispatch();

  return (
    <Container>
      <ProductImage src={product.image} alt={product.name} />

      <span>{product.name}</span>

      <span>{toLocalePrice(product.price)}</span>

      <span>
        <strong>{toLocalePrice(amount * product.price)}</strong>
      </span>

      <AmountInput
        type={"number"}
        min={"1"}
        max={"40"}
        value={amount}
        onChange={ev => {
          if (ev.target.valueAsNumber <= 40) {
            dispatch(updateCartAction(product, ev.target.valueAsNumber));
          }
        }}
      />

      <RemoveButton onClick={() => dispatch(removeFromCartAction(product))}>
        <FiTrash />
      </RemoveButton>
    </Container>
  );
};

export default CartItem;
