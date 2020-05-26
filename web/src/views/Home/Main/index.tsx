import React from "react";

import { Route, Switch } from "react-router-dom";

import { Header, Sidebar, Footer, GuestRoute } from "~/components";

import { NotFound } from "~/views/Errors";

import { Cart, Login, Post, Products, Register, Home } from "~/views/Home";

import { Container } from "./styles";

const HomeMain: React.FC = () => {
  return (
    <>
      <Container>
        <Header />
        <main>
          <Switch>
            <Route exact path={"/"} component={Home} />

            <Route exact path={"/posts/:post"} component={Post} />
            <Route exact path={"/products"} component={Products} />
            <Route exact path={"/cart"} component={Cart} />

            <GuestRoute exact path={"/login"} component={Login} />
            <GuestRoute exact path="/register" component={Register} />

            <Route path={"*"} component={NotFound} />
          </Switch>

          <Sidebar />
        </main>
      </Container>
      <Footer />
    </>
  );
};

export default HomeMain;
