import React, { useState } from "react";

import { Link } from "react-router-dom";
import { toast } from "react-toastify";

import { useDispatch } from "react-redux";
import { loginRequestAction } from "~/store/modules/auth/actions";

import { auth as service, errors, locale } from "~/services";

import { Container, Field, Form, Input, Submit } from "./styles";

import { toastMessage } from "~/utils";

const HomeRegister: React.FC = () => {
  const [email, setEmail] = useState("");
  const [name, setName] = useState("");
  const [userName, setUserName] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [password, setPassword] = useState("");

  const dispatch = useDispatch();

  async function handleSubmit(event: React.FormEvent) {
    const localeRegisterAction = locale.getTranslation("action.register");

    toast.warn(
      toastMessage(
        locale
          .getTranslation("notification.try")
          .replace("$action", localeRegisterAction)
      )
    );

    if (confirmPassword !== password) {
      setTimeout(() => {
        toast.error(
          toastMessage(locale.getTranslation("error.password.does.not.match"))
        );
      }, 1000);

      return;
    }

    try {
      event.preventDefault();

      await service.register({
        password,
        email,
        // eslint-disable-next-line @typescript-eslint/camelcase
        user_name: userName,
        name
      });

      const localeNotificationSuccessMessage = locale.getTranslation(
        "notification.success"
      );

      setTimeout(() => {
        toast.success(
          toastMessage(
            localeNotificationSuccessMessage.replace(
              "$action",
              localeRegisterAction
            )
          )
        );
      }, 1000);

      dispatch(loginRequestAction(email, password));

      setTimeout(() => {
        toast.success(
          localeNotificationSuccessMessage.replace(
            "$action",
            locale.getTranslation("action.login")
          )
        );
      }, 1000);
    } catch (exception) {
      errors.handleForException(exception);
    }
  }

  const localeTypeMessage = locale.getTranslation("message.type.the.thing");
  const localeRegisterAction = locale.getTranslation("action.register");

  const localeEmailMessage = locale.getTranslation("message.email");
  const localeNameMessage = locale.getTranslation("message.name");
  const localePasswordMessage = locale.getTranslation("message.password");
  const localeUserNameMessage = locale.getTranslation("message.user_name");
  const localeConfirmPasswordMessage = locale.getTranslation(
    "message.confirm.password"
  );

  return (
    <Container>
      <h1>{localeRegisterAction}</h1>
      <Form onSubmit={handleSubmit}>
        <Field>
          <span>{localeNameMessage}</span>

          <Input
            type={"text"}
            value={name}
            placeholder={localeTypeMessage.replace("$thing", localeNameMessage)}
            onChange={event => setName(event.target.value)}
          />
        </Field>

        <Field>
          <span>{localeUserNameMessage}</span>

          <Input
            type={"text"}
            value={userName}
            placeholder={localeTypeMessage.replace(
              "$thing",
              localeUserNameMessage
            )}
            onChange={event => setUserName(event.target.value)}
          />
        </Field>

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

        <Field>
          <span>{localeConfirmPasswordMessage}</span>

          <Input
            type={"password"}
            value={confirmPassword}
            placeholder={localeTypeMessage.replace(
              "$thing",
              localeConfirmPasswordMessage
            )}
            onChange={event => setConfirmPassword(event.target.value)}
          />
        </Field>

        <Submit>{localeRegisterAction.toUpperCase()}</Submit>

        <Link to={"/login"}>
          {locale.getTranslation("message.already.have.an.account")}
        </Link>
      </Form>
    </Container>
  );
};

export default HomeRegister;
