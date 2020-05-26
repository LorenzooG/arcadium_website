import getApi from "~/services";

import User, { UserConstructor } from "~/services/entities/User";

type AuthResponse = {
  token: string;
};

class AuthService {
  public async login(email: string, password: string): Promise<AuthResponse> {
    const api = getApi();

    const response = await api.post<AuthResponse>("auth/login", {
      email,
      password
    });

    return response.data;
  }

  public async register(data: object): Promise<User> {
    const api = getApi();

    const response = await api.post<UserConstructor>("users", data);

    return User.new(response.data);
  }
}

export default new AuthService();
