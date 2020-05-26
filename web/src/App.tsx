import React from "react";

import { PersistGate } from "redux-persist/integration/react";
import { Provider } from "react-redux";

import Routes from "~/Routes";
import store, { persistor } from "~/store";

import GlobalStyle from "~/styles/GlobalStyle";

import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.min.css";

const App: React.FC = () => (
  <>
    <Provider store={store}>
      <PersistGate loading={null} persistor={persistor}>
        <Routes />
      </PersistGate>
    </Provider>

    <ToastContainer
      position={"top-right"}
      autoClose={3 * 1000}
      hideProgressBar={false}
      newestOnTop={false}
      rtl={false}
      pauseOnHover
      pauseOnFocusLoss
      draggable
    />
    <GlobalStyle />
  </>
);

export default App;
