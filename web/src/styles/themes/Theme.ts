import { DefaultTheme } from "styled-components";
import { Colors, Options } from "~/styles/themes";

class Theme implements DefaultTheme {
  constructor(
    public name: string,
    public colors: Colors,
    public options: Options
  ) {
  }
}

export default Theme;
