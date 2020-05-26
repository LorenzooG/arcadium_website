import React from "react";

import { FiXOctagon } from "react-icons/fi";

import { locale } from "~/services";

import { Container, Error } from "./styles";

type Props = {
  error?: string;
};

const ErrorComponent: React.FC<Props> = ({
  error = locale.getTranslation("error.default")
}) => {
  return (
    <Container>
      <Error>
        <div>
          <FiXOctagon />
        </div>
        <span>{error?.toUpperCase()}</span>
      </Error>
    </Container>
  );
};

export default ErrorComponent;
