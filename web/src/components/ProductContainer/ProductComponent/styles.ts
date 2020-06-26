import styled from 'styled-components'

export const Container = styled.div`
  img {
    width: 120px;
    height: 120px;
  }
  text-align: center;
  box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;
  padding: 1em;

  height: fit-content;
  border-radius: 6px;

  h3 {
    margin-top: 0.5em;
  }

  small {
    display: block;
    margin-bottom: 2em;
  }

  > div {
    display: flex;
    flex-direction: column;
  }
`

export const Button = styled.button`
  margin-top: 8px;
  padding: 1em;
  border: none;
  outline: none;
  font-weight: bold;
  cursor: pointer;
  color: #fff;
  flex: 1;
  border-radius: 6px;
  transition: 200ms;

  :hover {
    filter: brightness(90%);
  }
`

export const AddButton = styled(Button)`
  background: #15a738;
`

export const DescriptionButton = styled(Button)`
  background: #2766c7;
`
