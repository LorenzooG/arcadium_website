import styled from "styled-components";

export const Container = styled.div`
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
  display: flex;
  flex-direction: column;
  justify-content: center;
  border-radius: 6px;

  > * {
    margin: auto;
  }

  padding: 3em;
`;
