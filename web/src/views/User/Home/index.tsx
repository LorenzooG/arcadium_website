import React, { FormEvent, useState } from "react";

import { useDispatch, useSelector } from "react-redux";
import { RootState } from "~/store/modules";
import { editAccountRequestAction } from "~/store/modules/auth/actions";

import AdminWrapper from "~/views/User/Admin/Main";
import { UserWrapper } from "~/components";

import { locale } from "~/services";
import { User } from "~/services/entities";

import { Body, Form, Header, Image, Input, SubmitButton } from "./styles";
import { requestPlayerHead } from "~/utils";

const UserHome: React.FC = () => {
  const user = useSelector<RootState, User | null>(state => state.auth.account);

  const [userName, setUserName] = useState(user?.userName);
  const [name, setName] = useState(user?.name);
  const [email, setEmail] = useState(user?.email);
  const [password, setPassword] = useState("");
  const [oldPassword, setOldPassword] = useState("");

  const dispatch = useDispatch();

  function handleUpdateUser(event: FormEvent) {
    event.preventDefault();

    dispatch(
      editAccountRequestAction(user?.id ?? 0, {
        // eslint-disable-next-line @typescript-eslint/camelcase
        user_name: userName,
        // eslint-disable-next-line @typescript-eslint/camelcase
        old_password: oldPassword,
        name,
        password: password?.length === 0 ? undefined : password,
        email
      })
    );
  }

  const localeTypeMessage = locale.getTranslation("message.type.the.thing");

  const localeUserEntity = locale.getTranslation("entity.user");

  const localeEmailMessage = locale.getTranslation("message.email");
  const localeNameMessage = locale.getTranslation("message.name");
  const localePasswordMessage = locale.getTranslation("message.password");
  const localeUserNameMessage = locale.getTranslation("message.user_name");
  const localeOldPasswordMessage = locale.getTranslation(
    "message.old.password"
  );

  return (
    <AdminWrapper path={localeUserEntity}>
      <UserWrapper>
        <Header>
          <h1>{localeUserEntity}</h1>
        </Header>

        <Body>
          <Form onSubmit={handleUpdateUser}>
            <Image
              src={requestPlayerHead(userName ?? "undefined")}
              alt={userName}
            />

            <Input
              type={"text"}
              placeholder={localeTypeMessage.replace(
                "$thing",
                localeNameMessage
              )}
              value={name}
              onChange={event => setName(event.target.value)}
            />

            <Input
              type={"email"}
              placeholder={localeTypeMessage.replace(
                "$thing",
                localeEmailMessage
              )}
              value={email}
              onChange={event => setEmail(event.target.value)}
            />

            <Input
              type={"text"}
              placeholder={localeTypeMessage.replace(
                "$thing",
                localeUserNameMessage
              )}
              value={userName}
              onChange={event => setUserName(event.target.value)}
            />

            <Input
              type={"password"}
              placeholder={localeTypeMessage.replace(
                "$thing",
                localePasswordMessage
              )}
              value={password}
              onChange={event => setPassword(event.target.value)}
            />

            <Input
              type={"password"}
              placeholder={localeTypeMessage.replace(
                "$thing",
                localeOldPasswordMessage
              )}
              value={oldPassword}
              onChange={event => setOldPassword(event.target.value)}
            />

            <SubmitButton type={"submit"}>
              {locale
                .getTranslation("action.update.entity")
                .replace("$entity", localeUserEntity)
                .toUpperCase()}
            </SubmitButton>
          </Form>
        </Body>
      </UserWrapper>
    </AdminWrapper>
  );
};

export default UserHome;
