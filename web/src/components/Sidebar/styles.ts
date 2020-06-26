import styled from 'styled-components'

export const Color = styled.div`
  background: #2766c7;
  border-top-left-radius: 2px;
  border-top-right-radius: 2px;
  padding: 1em;
  * {
    color: #f9f9f9;
  }
`

export const Field = styled.h3`
  strong {
    font-size: 16px;
    margin-right: 4px;
  }
  margin: 6px 0;
  font-weight: normal;
`

export const ButtonWrapper = styled.div`
  padding: 2em;
`

export const Button = styled.button`
  border: 3px solid;
  border-image-source: linear-gradient(to left, #245fb9 0%, #2c85f1);
  border-image-slice: 1;
  color: #333;
  font-weight: bold;
  background: #fff;
  padding: 1em 2em;
  cursor: pointer;
  outline: none;
  transition: 200ms;
  :hover {
    filter: brightness(90%);
  }
`

export const Container = styled.div`
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
  background: #fff;
  height: fit-content;
  border-radius: 6px;
  text-align: center;

  img {
    width: 82px;
    height: 82px;
    border-radius: 6px;
  }
`

export const Wrapper = styled.aside`
  iframe {
    border: none;
    width: 100%;
  }

  > *:nth-child(n + 2) {
    margin-top: 16px;
  }
`
