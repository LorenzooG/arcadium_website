import styled from "styled-components";

export const List = styled.ul`
  display: flex;
  flex-wrap: wrap;
`;

export const FakeChart = styled.div`
  padding: 8em 1em;

  display: flex;
  justify-content: center;
  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(0, 0, 0, 0.05);
  margin: 1em;
  border-radius: 6px;
`;

export const Item = styled.div`
  margin: 1em;
  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(0, 0, 0, 0.05);
  padding: 2em 1em;
  border-radius: 6px;
  flex: 1;

  h2 {
    font-size: 20px;
    font-weight: normal;
  }
`;
