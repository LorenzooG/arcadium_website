import styled from 'styled-components'

export const List = styled.ul`
  display: flex;
`

export const Nav = styled.div<{ open: boolean }>`
  display: flex;
  @media (max-width: 700px) {
    display: ${props => (props.open ? 'flex' : 'none')};
    justify-content: initial;
    flex-direction: column;
    ul {
      display: flex;
      flex-direction: column;
    }
    li {
      display: flex;
      a {
        text-align: center;
        width: 100%;
      }
    }
  }
  width: 100%;
  justify-content: space-between;
`

export const Toggler = styled.span`
  display: none;
  padding: 14px;
  cursor: pointer;
  @media (max-width: 700px) {
    display: flex;
  }
  svg {
    * {
      color: #fff;
    }
    font-size: 54px;
    margin-left: auto;
  }
`

export const Container = styled.div`
  margin: auto;
  width: 100%;
  max-width: 1000px;
  background: transparent;
`

export const User = styled.div`
  img {
    width: 64px;
    height: 64px;
    padding: 12px;
    cursor: pointer;
    transition: 200ms;
    :hover {
      transform: scale(1.3);
      filter: brightness(80%);
    }
  }
  list-style: none;
  > div {
    align-items: center;
    justify-content: center;
    width: fit-content;
    margin: auto;
    display: flex;
  }
  display: flex;

  @media (max-width: 700px) {
    margin-top: 20px;
  }
  span {
    color: #fff;
    strong {
      color: #fff;
    }
  }

  a {
    margin: auto;
  }
`

export const ContainerWrapper = styled.div`
  background: #2766c7;
  display: flex;
  height: 100%;
  border-bottom: 6px solid #254fa0;
`

export const LogOut = styled.button`
  padding: 1em 2em;
  background: transparent;
  border: none;
  outline: none;
  cursor: pointer;

  * {
    color: #fff;
  }

  svg {
    font-size: 30px;
  }
`

export const Item = styled.li`
  a {
    :hover {
      background: #254fa0;
    }
    transition: 200ms;
    color: #f9f9f9;
    display: block;
    text-decoration: none;
    padding: 2em;
  }
`
