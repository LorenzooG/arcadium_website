import styled from "styled-components";

export const Container = styled.div`
  > div {
    display: flex;
    padding-left: 250px;

    transition: padding 200ms;
    @media (max-width: 1000px) {
      padding-left: 0;
    }
  }
  background: rgba(76, 76, 255, 0.09);
  height: fit-content;
  min-height: calc(100vh - 66px);
`;

export const Page = styled.div`
  height: fit-content;
  min-height: calc(100vh - 66px);
`;
