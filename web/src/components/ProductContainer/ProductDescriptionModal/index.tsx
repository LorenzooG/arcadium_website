import React, { useEffect } from "react";

import { useDispatch } from "react-redux";
import { addToCartAction } from "~/store/modules/cart/actions";

import { toLocalePrice } from "~/utils";
import { markdown, locale } from "~/services";
import { Product } from "~/services/entities";

import {
  Container,
  Content,
  Header,
  AddToCartButton,
  BackButton,
  Footer,
  Main
} from "./styles";

type Props = {
  product: Product;
  open: boolean;
  setOpen: (value: boolean) => void;
};

const ProductDescriptionModal: React.FC<Props> = ({
  open,
  setOpen,
  product
}) => {
  useEffect(() => {
    if (open) {
      document.body.style.overflow = "hidden";
    }

    return () => {
      document.body.style.overflow = "auto";
    };
  }, [open]);

  const dispatch = useDispatch();

  if (!open) {
    return null;
  }

  function handleAddToCart() {
    dispatch(addToCartAction(product));
  }

  return (
    <Container>
      <Main>
        <Header>
          <img src={product.image} alt={product.name} />

          <span>
            <h3>{product.name}</h3>

            <small>{toLocalePrice(product.price)}</small>
          </span>
        </Header>

        <Content>
          <p
            dangerouslySetInnerHTML={{
              __html: markdown.render(product.description)
            }}
          />
        </Content>

        <Footer>
          <BackButton onClick={() => setOpen(false)}>
            {locale.getTranslation("action.back").toUpperCase()}
          </BackButton>

          <AddToCartButton onClick={handleAddToCart}>
            {locale.getTranslation("action.add.to.cart").toUpperCase()}
          </AddToCartButton>
        </Footer>
      </Main>
    </Container>
  );
};

export default ProductDescriptionModal;
