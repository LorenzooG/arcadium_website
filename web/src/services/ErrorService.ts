import { AxiosError } from "axios";

import { toast } from "react-toastify";

import { toastMessage } from "~/utils";

import _errors from "./locale/errors/ptBR";

import { locale } from "~/services";

type Locale = {
  [key: string]: string;
};

const errors: Locale = _errors;

class MessageService {
  public handle(message: string, prop = "") {
    const translations = locale.allTranslations();

    for (const regex of Object.keys(errors)) {
      const pattern = new RegExp(regex);

      const found = pattern.exec(message);

      if (found === null) continue;

      message = errors[regex]
        .replace("$field", translations[`message.${prop}`])
        .replace("$length", found[2])
        .replace("$type", found[2]);
    }

    toast.error(toastMessage(message));
  }

  public handleForException(error: AxiosError) {
    const { response } = error;

    if (response?.data?.message) {
      this.handle(response.data.message);
    }

    if (response?.data?.errors) {
      type Errors = {
        [key: string]: string[];
      };

      const responseErrors: Errors = response.data.errors;

      setTimeout(() => {
        for (const field of Object.keys(responseErrors)) {
          for (const errorMessage of responseErrors[field]) {
            this.handle(errorMessage, field);
          }
        }
      }, 100);

      return;
    }

    this.handle(
      locale.getTranslation("error.unexpected").replace("$error", error.message)
    );
  }
}

export default new MessageService();
