export default async function requestServerInfo(address: string) {
  return fetch(
    `https://mcstatus.snowdev.com.br/api/query/${address}`
  ).then(res => res.json());
}
