import styled from "styled-components";

export const Container = styled.div`
  > main {
    max-width: 1000px;
    margin: 72px auto;
    display: grid;
    gap: 18px;
    grid-template-columns: 3fr 1fr;
    padding-right: 16px;
    padding-left: 16px;
  }

  @media (max-width: 850px) {
    > main {
      grid-template-columns: 1fr;
    }
    aside {
      order: -1;
    }
  }
`;
