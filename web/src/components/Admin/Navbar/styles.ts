import styled from "styled-components";

export const Container = styled.nav<{ sidebarOpen: boolean }>`
  display: flex;
  width: 100%;
  align-items: center;
  height: fit-content;

  transition: margin 200ms;

  overflow: hidden;

  @media (max-width: 1000px) {
    margin-left: ${props => (props.sidebarOpen ? "250px" : "0")};
  }

  background: #625ee2;

  border-bottom: 6px solid #5a57d0;

  * {
    color: #fff;
  }

  h3 {
    display: block;
    padding: 1em;
  }
`;

export const List = styled.ul`
  display: flex;
`;

export const Item = styled.li`
  a {
    display: block;
    padding: 1em;
    text-decoration: none;
  }
`;

export const ReturnToTheSite = styled.li`
  a {
    display: block;
    padding: 1em;
    text-decoration: none;
    font-weight: bold;
  }

  margin-left: auto;
`;

export const Toggler = styled.div`
  display: none;
  padding: 14px;
  cursor: pointer;
  @media (max-width: 1000px) {
    display: flex;
  }
  svg {
    * {
      color: #fff;
    }
    font-size: 28px;
    margin-left: auto;
  }
`;

export const Nav = styled.div<{ open: boolean }>`
  justify-content: space-between;
`;

export const UserStyle = styled(Item)``;
