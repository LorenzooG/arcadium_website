import styled from 'styled-components'

export const Container = styled.div`
  width: 100%;
  height: 100%;
`

export const Error = styled.div`
  margin: auto;
  display: flex;
  //border: 1px solid #d81b21;
  border-radius: 6px;
  flex-direction: column;
  align-items: center;
  background: #fff;

  * {
    color: #333 !important;
    font-weight: bold;
  }

  > div {
    padding: 2em;
    height: 100%;
    svg {
      font-size: 48px;
      * {
        color: #d81b21 !important;
      }
    }
  }

  > span {
    padding: 1em 1em;
  }
`
