import React from "react";

import { ToastMessage } from "../styles";

export default function toastMessage(msg: string) {
  return <ToastMessage>{msg}</ToastMessage>;
}
