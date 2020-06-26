import styled from 'styled-components'

import dirt from '~/assets/header_background.jpeg'

export const Wrapper = styled.header`
  height: 260px;
  background-image: url(${dirt});
  background-repeat: repeat;
  @media (max-width: 610px) {
    min-height: 400px;
    display: flex;
    justify-content: center;
    height: fit-content;
  }
`

export const Container = styled.div`
  width: 100%;
  max-width: 1000px;
  margin: auto;
  height: 100%;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
`

export const CartText = styled.div`
  h4 {
    margin: auto;
  }
`

export const ItemWrapper = styled.div`
  display: flex;
  padding: 1em;
  height: 100%;
  justify-content: center;
  @media (max-width: 610px) {
    width: 100%;
  }
`

export const CartWrapper = styled(ItemWrapper)`
  display: flex;
  justify-content: center;
  padding: 32px;

  > a {
    > div {
      padding: 2em;
    }
    margin: auto;
    background: #fff;
    border: 1px solid #2766c7;
    display: flex;
    border-radius: 6px;
  }
`

export const CartIcon = styled.span`
  background: #2766c7;
  padding: 2em;
  display: flex;
  justify-content: center;
  svg {
    * {
      color: #fff;
    }
    margin: auto;
  }
`

export const Title = styled(ItemWrapper)`
  h1 {
    color: #fff;
    opacity: 1;
    font-size: 56px;

    filter: none;
    margin: auto;
  }
`
