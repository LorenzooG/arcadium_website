import styled from 'styled-components'

export const Container = styled.li`
  grid-template-columns: 1fr 6fr 1fr 1fr 60px 1fr;
  display: grid;
  gap: 30px;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid #ddd;

  @media (max-width: 800px) {
    box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
    margin: 8px;
    grid-template-columns: 1fr;
    text-align: center;
    border-radius: 6px;
    gap: 10px;
  }
`

export const AmountInput = styled.input`
  width: 100%;
  border-radius: 6px;
  border: 1px solid #999;

  :focus {
    filter: brightness(90%);
  }
  outline: none;

  padding: 8px;

  @media (max-width: 800px) {
    width: fit-content;
    margin: 12px auto;
  }
`

export const RemoveButton = styled.button`
  border: 0;
  background: #d0282e;
  cursor: pointer;
  * {
    color: #fff;
  }
  border-radius: 50%;
  svg {
    font-size: 20px;
  }
  outline: none;
  padding: 6px;

  @media (max-width: 800px) {
    position: absolute;
    margin: 0 8px;
  }
`

export const ProductImage = styled.img`
  width: 48px;
  height: 48px;
  margin: 16px auto;
  border-radius: 30%;
`
