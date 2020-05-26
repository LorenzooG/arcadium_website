import styled from "styled-components";

export const Container = styled.section`
  display: flex;
  flex-direction: column;
  max-width: 100%;
  background: #fff;
  overflow: auto;
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
  border-radius: 6px;
  height: fit-content;

  padding: 12px 12px 24px;

  ul {
    flex: 1;
    border-radius: 6px;
    padding: 8px 1em;
  }
`;

export const Title = styled.h1`
  margin-bottom: 1em;
  margin-left: 16px;
  display: flex;
  align-items: center;
`;

export const ClearCartButton = styled.button`
  background: #d0282e;
  border: none;
  outline: none;
  padding: 8px;
  color: #fff;
  margin-left: 2em;
  font-weight: bold;
  cursor: pointer;
  border-radius: 6px;
`;

export const EmptyCart = styled.div`
  display: flex;
  justify-content: center;

  padding: 4em 0;

  h1 {
    font-weight: normal;
    font-size: 20px;
  }
`;
