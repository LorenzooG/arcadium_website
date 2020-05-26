import styled from "styled-components";

export const Header = styled.header`
  padding: 2em !important;
`;

export const Body = styled.div`
  padding: 24px !important;
`;

export const Input = styled.input`
  display: flex;
  width: 70%;
  outline: none;
  padding: 12px;
  border: 1px solid #999;
  border-radius: 6px;
  margin: 12px auto 8px;
`;

export const Form = styled.form`
  display: flex;
  flex-direction: column;
`;

export const Image = styled.img`
  width: 200px;
  height: 200px;
  margin: 24px auto;
`;

export const SubmitButton = styled.button`
  cursor: pointer;
  padding: 20px;
  outline: none;

  background: #625ee2;

  color: #fff;
  border-radius: 10px;
  font-weight: bold;

  border: 1px solid #43429c;

  margin: 18px auto;

  :hover {
    filter: brightness(90%);
  }
`;
