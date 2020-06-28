import styled from 'styled-components'

export const Container = styled.div`
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
  flex-direction: column;
  width: 100%;
  border-radius: 6px;
  animation: 1s ease-in-out appear;

  margin: 12px 0;

  * {
    color: #333;
  }
`

export const Content = styled.p`
  padding: 20px;
  display: block;
`

export const Header = styled.header`
  background: #2766c7;
  border-top-left-radius: 6px;
  border-top-right-radius: 6px;
  border-bottom: 6px solid #254fa0;
  padding: 6px;
  display: flex;
  align-items: center;
  span {
    font-size: 13px;
  }
  * {
    color: #fff;
  }
`

export const UserAvatar = styled.img`
  width: 28px;
  height: 28px;
  margin: 2px 12px;
  border-radius: 8px;
  border: #f7f7f7 solid 2px;
`
