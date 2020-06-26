import styled from 'styled-components'

export const Container = styled.div`
  padding: 1em 0;

  h1 {
    margin-bottom: 1em;
  }
`

export const Field = styled.h3`
  font-weight: normal;
  display: flex;
  align-items: center;

  margin-bottom: 8px;

  img {
    margin-left: 8px;
    width: 28px !important;
    height: 28px !important;
  }

  span {
    margin-left: 8px;
    font-weight: bold;
  }
`

export const SubProduct = styled.div`
  box-shadow: 0 2px 3px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(0, 0, 0, 0.05);
  width: fit-content;
  padding: 8px;
  text-align: left;
  padding: 6px;
  margin: 8px;
`

export const SubField = styled.div`
  img {
    margin-left: 8px;
    width: 28px !important;
    height: 28px !important;
  }

  span {
    margin-left: 8px;
    font-weight: bold;
  }
`
