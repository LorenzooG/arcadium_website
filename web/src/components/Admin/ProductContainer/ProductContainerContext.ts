import { createContext } from "react";
import { Product } from "~/services/entities";

type ContextType = {
  addProduct: (post: Product) => void;
  deleteProduct: (id: number) => void;
  updateProduct: (id: number, post: Product) => void;
};

const ProductContainerContext = createContext<ContextType>({
  addProduct: () => {
    return;
  },
  updateProduct: () => {
    return;
  },
  deleteProduct: () => {
    return;
  }
});

export default ProductContainerContext;
