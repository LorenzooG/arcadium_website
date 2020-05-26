export type Colors = {
  primary: string;
  secondary: string;
  tertiary: string;

  background: string;
  text: string;
};

export type Options = {
  containerSize: number;
  containerHeight: number;
  mobileLimit: number;
  minWidth: number;
  sidebarSize: number;
};

declare module "styled-components" {
  export interface DefaultTheme {
    name: string;
    colors: Colors;
    options: Options;
  }
}
