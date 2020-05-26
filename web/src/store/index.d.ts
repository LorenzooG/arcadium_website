import { AuthState } from "~/store/modules/auth/reducer";
import { CartState } from "~/store/modules/cart/reducer";
import { ProductsState } from "~/store/modules/products/reducer";

declare module "react-redux" {
  export interface DefaultRootState {
    auth: AuthState;
    cart: CartState;
    products: ProductsState;
  }
}
