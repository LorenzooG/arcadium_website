import ptBR from "./locale/ptBR";

type Translations = {
  [key: string]: string;
};

class LocaleService {
  public getTranslation(msg: keyof typeof ptBR): string {
    return ptBR[msg];
  }

  public allTranslations(): Translations {
    return ptBR;
  }
}

export default new LocaleService();
