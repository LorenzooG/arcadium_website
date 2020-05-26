import styled from "styled-components";

export const Container = styled.aside<{ open: boolean }>`
  background: #5a57d0;
  @media (max-width: 1000px) {
    margin-left: ${props => (props.open ? "0" : "-250px")};
  }

  transition: margin 200ms;

  width: 250px;

  position: fixed;

  height: 100%;

  left: 0;
  top: 0;

  img {
    width: 64px;
    height: 64px;
    margin: auto 0;
  }

  span {
    margin: 1em 0 1em 12px;
    word-break: break-word;
  }

  * {
    color: #fff;
    font-weight: bold;
  }

  div {
    display: flex;
    background: #43419a;
    align-items: center;
    padding: 8px;
  }

  display: flex;

  flex-direction: column;
`;

export const List = styled.ul`
  display: flex;
  padding: 20px 8px;
  flex-direction: column;
`;

export const Item = styled.li`
  width: 100%;
  a {
    display: block;
    width: 100%;
    background: #5a57d0;
    padding: 14px 12px;
    text-decoration: none;
    font-weight: normal;
    color: #fff;
    :hover {
      filter: brightness(90%);
    }
  }
`;
