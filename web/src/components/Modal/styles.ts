import styled from "styled-components";

export const Container = styled.div`
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;

  overflow: auto;

  display: flex;

  justify-content: center;

  text-align: initial;

  background: rgba(0, 0, 0, 0.9);
`;

export const Button = styled.button`
  padding: 1em;
  border: none;
  outline: none;
  font-weight: bold;
  cursor: pointer;
  color: #fff;
  flex: 1;
  border-radius: 6px;
  transition: 200ms;

  :hover {
    filter: brightness(90%);
  }
`;

export const BackButton = styled(Button)`
  background: transparent;
  flex: none;

  * {
    color: #fff;
    font-size: 24px;
  }

  margin-left: auto;
`;

export const SubmitButton = styled(Button)`
  background: #15a738 !important;
`;

export const Main = styled.div`
  width: 650px !important;
  @media (max-width: 650px) {
    width: 100%;
  }
  margin: auto;
  background: #f9f9f9;
  border-radius: 6px;
`;

export const Content = styled.main`
  padding: 2em;
  display: flex;
  flex-direction: column;
  min-height: 200px;
`;

export const Header = styled.header`
  display: flex;
  align-items: center;

  background: #625ee2;

  border-bottom: 6px solid #5a57d0;

  padding: 2em;

  h3 {
    margin: 0;
  }

  * {
    color: #fff;
  }

  border-top-left-radius: 4px;
  border-top-right-radius: 4px;

  img {
    width: 84px;
    height: 84px;
    background: #fff;
    border-radius: 20%;
    border: 1px solid #dddd;
    margin-right: 12px;
  }
`;

export const Footer = styled.footer`
  padding: 1em 2em;
  background: #625ee2;

  border-top: 6px solid #5a57d0;

  button {
    margin-right: 1em;
    background: #fff;
  }

  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 4px;
`;
