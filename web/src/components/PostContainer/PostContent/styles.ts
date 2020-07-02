import styled from 'styled-components'

export const Content = styled.p`
  font-size: 16px;
  margin: 16px 0;
`

export const Title = styled.h2`
  margin: 6px 0;
  font-size: 22px;
  text-align: center;
`
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
`
