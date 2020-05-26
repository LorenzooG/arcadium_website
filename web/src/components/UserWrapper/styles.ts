import styled from "styled-components";

export const Container = styled.div`
  background: #ffff;
  border-radius: 10px;

  > header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    background: #f4f4f4;
    padding: 2em;
    h1 {
      color: #6662ec;
    }
  }

  > div {
    padding: 3px;
  }

  width: 100%;
`;

export const Wrapper = styled.div`
  background: #625ee2;
  padding: 3px 0;
  border-radius: 12px;

  width: 100%;

  max-width: 1000px;

  @media (max-width: 1300px) {
    max-width: none;

    margin: 3em 1em;
  }

  height: fit-content;

  margin: 3em auto;

  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
`;
