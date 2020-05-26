import { createContext } from "react";

import { User } from "~/services/entities";

type ContextType = {
  addUser: (post: User) => void;
  deleteUser: (id: number) => void;
  updateUser: (id: number, post: User) => void;
};

const UserContainerContext = createContext<ContextType>({
  addUser: () => {
    return;
  },
  updateUser: () => {
    return;
  },
  deleteUser: () => {
    return;
  }
});

export default UserContainerContext;
