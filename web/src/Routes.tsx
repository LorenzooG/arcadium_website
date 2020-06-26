import React from "react";

import { PrivateRoute } from "~/components";

import { Route, Router, Switch } from "react-router-dom";

import Main from "./views/Home/Main";

import { UserHome } from "~/views/User";

import { AdminHome, AdminPayments, AdminPosts, AdminProducts, AdminUsers } from "~/views/User/Admin";

import { history } from "~/services";

const Routes: React.FC = () => {
  return (
    <Router history={history}>
      <Switch>
        <PrivateRoute exact path={"/user"} component={UserHome}/>

        <PrivateRoute
          onlyAdmin
          exact
          path={"/admin/users"}
          component={AdminUsers}
        />

        <PrivateRoute
          onlyAdmin
          exact
          path={"/admin/products"}
          component={AdminProducts}
        />

        <PrivateRoute
          onlyAdmin
          exact
          path={"/admin/posts"}
          component={AdminPosts}
        />

        <PrivateRoute
          onlyAdmin
          exact
          path={"/admin/payments"}
          component={AdminPayments}
        />

        <PrivateRoute onlyAdmin path={"/admin"} component={AdminHome}/>

        <Route path={"/"} component={Main}/>
      </Switch>
    </Router>
  );
};

export default Routes;
