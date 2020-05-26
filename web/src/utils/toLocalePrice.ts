export default function toLocalePrice(num: number) {
  return num.toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL"
  });
}
