import React, { useEffect, useState } from "react";

import { Link } from "react-router-dom";

import { requestPlayerHead, toLocalePrice } from "~/utils";

import { useDispatch, useSelector } from "react-redux";
import { RootState } from "~/store/modules";
import { Item } from "~/store/modules/cart/reducer";
import { checkoutCartAction } from "~/store/modules/cart/actions";

import { locale } from "~/services";
import { User } from "~/services/entities";

import {
  Container,
  CheckoutButton,
  Info,
  Section,
  UserNameInput,
  UserImage,
  LoginFirst
} from "./styles";

type Props = {
  items: Item[];
};

const CartCheckout: React.FC<Props> = ({ items }) => {
  const account = useSelector<RootState, User | null>(
    state => state.auth.account
  );

  const blocked = useSelector<RootState>(state => state.cart.blocked);

  const [totalPrice, setTotalPrice] = useState(0);
  const [userName, setUserName] = useState(account?.userName);

  const dispatch = useDispatch();

  useEffect(() => {
    let _totalPrice = 0;

    for (const item of items) {
      _totalPrice += item.amount * item.product.price;
    }

    setTotalPrice(_totalPrice);
  }, [items]);

  return (
    <Container>
      {account ? (
        <div>
          <h1>{locale.getTranslation("action.checkout.cart")}</h1>

          <Section>
            <h3>{locale.getTranslation("message.select.user.name")}</h3>

            <div>
              <UserImage
                src={requestPlayerHead(userName ?? "undefined")}
                alt={userName}
              />

              <UserNameInput
                type={"text"}
                value={userName}
                onChange={event => setUserName(event.target.value)}
              />
            </div>
          </Section>

          <Section>
            <h3>{locale.getTranslation("message.check.the.info")}</h3>

            <Info>
              {locale.getTranslation("message.payment.method")}:
              <span>{locale.getTranslation("message.payment.method.mp")}</span>
            </Info>

            <Info>
              {locale.getTranslation("message.user_name")}:
              <span>{userName}</span>
            </Info>

            <Info>
              {locale.getTranslation("message.total.price")}:
              <span>{toLocalePrice(totalPrice)}</span>
            </Info>
          </Section>

          <CheckoutButton
            onClick={() =>
              !blocked && dispatch(checkoutCartAction(userName ?? "", items))
            }
          >
            {locale.getTranslation("action.checkout.cart").toUpperCase()}
          </CheckoutButton>
        </div>
      ) : (
        <LoginFirst>
          <div>
            <h1>{locale.getTranslation("message.login.first")}</h1>

            <Link to={"/login"}>
              {locale.getTranslation("action.login").toUpperCase()}
            </Link>
          </div>
        </LoginFirst>
      )}
    </Container>
  );
};

export default CartCheckout;
