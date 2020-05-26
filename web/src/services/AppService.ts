class AppService {
  public publicUrl(): string {
    return process.env.PUBLIC_URL;
  }

  public name(): string {
    const name = process.env.REACT_APP_NAME;

    if (!name) {
      throw new Error("Do not found app name");
    }

    return name;
  }

  public serverAddress(): string {
    const address = process.env.REACT_APP_SERVER_ADDRESS;

    if (!address) {
      throw new Error("Do not found server address");
    }

    return address;
  }

  public discordServerId(): string {
    const id = process.env.REACT_APP_DISCORD_SERVER_ID;

    if (!id) {
      throw new Error("Do not found discord server id");
    }

    return id;
  }
}

export default new AppService();
