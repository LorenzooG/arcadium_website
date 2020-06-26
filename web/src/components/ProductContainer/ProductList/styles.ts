import styled from 'styled-components'

export const Container = styled.div`
  ul {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;

    @media (max-width: 600px) {
      grid-template-columns: 1fr;
    }
  }
`
