import React, { FormEvent, useState } from "react";

import { Link } from "react-router-dom";

import { useDispatch } from "react-redux";
import { loginRequestAction } from "~/store/modules/auth/actions";

import { locale } from "~/services";

import { Container, Field, Submit, Input, Form } from "./styles";

const HomeLogin: React.FC = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const dispatch = useDispatch();

  async function handleSubmit(event: FormEvent) {
    event.preventDefault();

    dispatch(loginRequestAction(email, password));
  }

  const localeTypeMessage = locale.getTranslation("message.type.the.thing");
  const localeLoginAction = locale.getTranslation("action.login");

  const localeEmailMessage = locale.getTranslation("message.email");
  const localePasswordMessage = locale.getTranslation("message.password");

  return (
    <Container>
      <h1>{localeLoginAction}</h1>
      <Form onSubmit={handleSubmit}>
        <Field>
          <span>{localeEmailMessage}</span>
          <Input
            type={"email"}
            value={email}
            placeholder={localeTypeMessage.replace(
              "$thing",
              localeEmailMessage
            )}
            onChange={event => setEmail(event.target.value)}
          />
        </Field>

        <Field>
          <span>{localePasswordMessage}</span>
          <Input
            type={"password"}
            value={password}
            placeholder={localeTypeMessage.replace(
              "$thing",
              localePasswordMessage
            )}
            onChange={event => setPassword(event.target.value)}
          />
        </Field>

        <Submit>{localeLoginAction.toUpperCase()}</Submit>
        <Link to={"register"}>
          {locale.getTranslation("message.do.not.have.an.account")}
        </Link>
      </Form>
    </Container>
  );
};

export default HomeLogin;
