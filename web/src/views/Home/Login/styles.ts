import styled from "styled-components";

export const Container = styled.div`
  box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.08) !important;
  display: flex;

  border-radius: 6px;

  flex-direction: column;
  padding: 1em;

  h1 {
    text-align: center;
  }
`;

export const Form = styled.form`
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 400px;

  margin: auto;

  @media (max-width: 850px) {
    width: 100%;
    padding: 10em 0;
  }

  a {
    font-size: 14px;
    color: #2766c7;
    text-decoration: none;
    margin-top: 6px;
  }
`;

export const Field = styled.label`
  display: flex;
  flex-direction: column;
  margin-bottom: 12px;

  span {
    font-size: 18px;
    margin-bottom: 4px;
  }
`;

export const Input = styled.input`
  padding: 12px 8px;
  outline: none;
  border: 1px solid #dddddd;
  border-radius: 6px;

  :focus {
    filter: brightness(90%);
  }
`;

export const Submit = styled.button`
  background: #2766c7;
  border: none;
  outline: none;
  color: #fff;
  padding: 1em;
  font-weight: bold;
  cursor: pointer;
  border-radius: 6px;

  margin-top: 2em;

  font-size: 15px;

  :hover {
    filter: brightness(90%);
  }
`;
