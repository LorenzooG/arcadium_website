import React from "react";

import { useLocation } from "react-router";

import HomeMain from "~/views/Home/Main";

import { locale } from "~/services";

const ErrorNotFound: React.FC = () => {
  const location = useLocation();

  return (
    <HomeMain>
      <h1>
        {locale
          .getTranslation("error.not.found.page")
          .replace("$path", location.pathname.toString())}
      </h1>
    </HomeMain>
  );
};

export default ErrorNotFound;
