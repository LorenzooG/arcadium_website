import styled from "styled-components";

export const ContainerListUl = styled.ul`
  display: flex;
  flex-direction: column;
  padding: 8px 12px;

  li {
    display: flex;
    width: 100%;
    flex-direction: column;
  }
`;

export const ContainerComponent = styled.div`
  display: grid;
  align-items: center;
  grid-template-columns: 92px 1fr 10fr 96px;

  > *:nth-child(1) {
    text-align: center;
  }

  > *:nth-child(2) {
    text-align: center;
  }

  border-radius: 8px;

  @media (max-width: 800px) {
    display: flex;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(0, 0, 0, 0.05);

    padding: 32px 18px;
  }

  margin-bottom: 8px;

  img {
    width: 64px;
    height: 64px;
  }
`;

export const ContainerComponentButton = styled.button`
  padding: 12px;
  border: none;
  outline: none;
  cursor: pointer;
  border-radius: 12px;

  margin-right: 8px;

  width: fit-content;

  @media (max-width: 800px) {
    display: inline-flex;
    margin: 12px;
    padding: 20px;
  }

  transition: 200ms;

  :hover {
    filter: brightness(90%);
  }

  * {
    font-size: 16px;
    color: #fff;
  }
`;

export const ContainerAddEntityButton = styled(ContainerComponentButton)`
  background: #1eb33e;

  margin: 12px 12px 12px auto !important;

  padding: 12px;

  svg {
    display: block;
  }

  * {
    font-size: 24px !important;
    color: #fff !important;
  }
`;

export const ContainerComponentDeleteButton = styled(ContainerComponentButton)`
  background: #e32c2a;
`;

export const ContainerComponentEditButton = styled(ContainerComponentButton)`
  background: #345ee3;
`;
