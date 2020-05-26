import React, { useEffect } from "react";

import { useDispatch, useSelector } from "react-redux";
import { RootState } from "~/store/modules";
import { fetchProductsRequestAction } from "~/store/modules/products/actions";

import { ErrorComponent } from "~/components";
import Loading from "./ProductList/Loading";
import ProductList from "./ProductList";

import { Product } from "~/services/entities";

type Redux = {
  products: Product[];
  loading: boolean;
  error: boolean;
};

const ProductContainer: React.FC = () => {
  const { products, loading, error } = useSelector<RootState, Redux>(
    state => state.products
  );

  const dispatch = useDispatch();

  useEffect(() => {
    if (loading) {
      dispatch(fetchProductsRequestAction());
    }
  }, [loading, dispatch]);

  if (loading) {
    return <Loading />;
  }

  if (error) {
    return <ErrorComponent error={"Error on fetch"} />;
  }

  return <ProductList products={products} />;
};

export default ProductContainer;
