import React, { useEffect, useState } from "react";

import produce from "immer";
import { useResource } from "~/utils";

import Error from "~/components/ErrorComponent";
import Loading from "./UserList/Loading";
import UserList from "./UserList";

import { users as service } from "~/services";
import { User } from "~/services/entities";

import ContainerContext from "./UserContainerContext";

const AdminUserContainer: React.FC = () => {
  const [_users, loading, error] = useResource<User[]>(service.fetchAll);
  const [users, setUsers] = useState(_users);

  useEffect(() => void setUsers(_users), [_users]);

  if (loading) {
    return <Loading />;
  }

  if (error) {
    return <Error />;
  }

  return (
    <ContainerContext.Provider
      value={{
        updateUser: (id, user) => {
          setUsers(
            produce(users, draft => {
              draft[users.findIndex(_user => _user.id === id)] = user;
            })
          );
        },
        addUser: user => {
          setUsers(
            produce(users, draft => {
              draft.push(user);
            })
          );
        },
        deleteUser: id => {
          setUsers(
            produce(users, draft => {
              draft.splice(
                users.findIndex(user => user.id === id),
                1
              );
            })
          );
        }
      }}
    >
      <UserList users={users} />
    </ContainerContext.Provider>
  );
};

export default AdminUserContainer;
