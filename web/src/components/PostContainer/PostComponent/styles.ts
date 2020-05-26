import styled from "styled-components";

export const Container = styled.li`
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
  flex-direction: column;
  width: 100%;
  background: #fff;
  border-radius: 6px;
  animation: 1s ease-in-out appear;
`;

export const Content = styled.div<{ defaultHeight?: boolean }>`
  margin: 8px 0;
  padding: 0 14px 14px;
  border-radius: inherit;
`;

export const ContentText = styled.p`
  font-size: 16px;
  margin: 16px 0;
`;

export const Title = styled.h2`
  margin: 6px 0;
  font-size: 22px;
  text-align: center;
`;

export const Header = styled.div`
  background: #2766c7;
  border-top-left-radius: 6px;
  border-top-right-radius: 6px;

  border-bottom: 6px solid #254fa0;

  padding: 8px;

  display: flex;

  align-items: center;

  svg {
    font-size: 32px;
    margin: 8px;
    border: 1px solid #fff;
    border-radius: 6px;
  }

  span {
    font-size: 13px;
  }

  * {
    color: #fff;
  }
`;

export const Fade = styled.div`
  display: flex;
  a {
    :hover {
      filter: brightness(80%);
    }
    transition: 200ms;
    display: block;
    font-size: 16px;
    color: #fff;
    margin: auto;
    font-weight: bold;
    text-decoration: none;
    background: #2766c7;
    padding: 12px;
  }
`;
