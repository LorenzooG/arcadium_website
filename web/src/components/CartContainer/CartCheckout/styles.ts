import styled from "styled-components";

export const Info = styled.h2`
  margin: 0;
  font-size: 16px;
  display: flex;
  font-weight: normal;
  align-items: center;
  padding-left: 1em;

  span {
    display: block;
    padding: 6px;
    border-radius: 6px;
    background: #e9e9e9;

    font-size: 14px;

    margin-left: 12px;

    border: 1px solid #dddd;
  }
`;

export const LoginFirst = styled.span`
  width: 100%;
  border-radius: 6px;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  padding: 4em;

  h1 {
    color: #fff;
  }

  > div {
    text-align: center;
    a {
      display: block;
      text-decoration: none;
      background: #1973d0;
      border: none;
      outline: none;
      color: #fff;
      margin: 12px auto;
      padding: 16px;
      width: fit-content;
      font-weight: bold;
      cursor: pointer;
      border-radius: 6px;

      :hover {
        filter: brightness(90%);
      }
    }
  }
`;

export const Section = styled.section`
  padding-left: 1em;
  padding-bottom: 1em;
  border-bottom: 1px solid #ddd;
  margin-bottom: 8px;

  > div {
    display: flex;
    align-items: center;
  }

  h3 {
    font-size: 20px;
    margin: 1em 0;
  }
`;

export const Container = styled.section`
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
  height: fit-content;
  border-radius: 6px;

  display: flex;
  flex-direction: column;
  background: #fff;
  margin-top: 1em;

  > div {
    margin: 12px 12px 1em;
  }

  h1 {
    margin-left: 16px;
  }

  h2 + h2 {
    margin-top: 6px;
  }
`;

export const CheckoutButton = styled.button`
  background: #1973d0;
  border: none;
  outline: none;
  color: #fff;
  padding: 16px;
  width: fit-content;
  margin-left: auto;
  font-weight: bold;
  cursor: pointer;
  border-radius: 6px;

  :hover {
    filter: brightness(90%);
  }

  float: right;
`;

export const UserNameInput = styled.input`
  margin-left: 1em;
  outline: none;
  border-radius: 6px;
  border: 1px solid #999;

  :focus {
    filter: brightness(90%);
  }

  padding: 8px;
`;

export const UserImage = styled.img`
  height: 72px;
  width: 72px;
`;
