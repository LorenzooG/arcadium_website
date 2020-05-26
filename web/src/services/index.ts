import axios from "axios";
import store from "~/store";

export { default as posts } from "./crud/PostService";
export { default as auth } from "./AuthService";
export { default as products } from "./crud/ProductService";
export { default as users } from "./crud/UserService";
export { default as payments } from "./crud/PaymentService";

export { default as markdown } from "./MarkdownService";
export { default as history } from "./HistoryService";
export { default as app } from "./AppService";
export { default as errors } from "./ErrorService";
export { default as locale } from "./LocaleService";

export default function getApi() {
  const state = store.getState();

  return axios.create({
    baseURL: process.env.REACT_APP_API_URL,
    headers: {
      Authorization: `Bearer ${state.auth.token}`
    }
  });
}
