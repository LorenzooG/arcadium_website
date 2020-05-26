import React from "react";

import { locale } from "~/services";

import { Container } from "./styles";

const ProductListEmpty: React.FC = () => {
  return (
    <Container>
      <h1>
        {locale
          .getTranslation("message.is.empty")
          .replace("$entity", locale.getTranslation("entity.product"))}
      </h1>
      <small>{locale.getTranslation("message.come.back.later")}</small>
    </Container>
  );
};

export default ProductListEmpty;
