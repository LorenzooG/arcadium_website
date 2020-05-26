import styled from "styled-components";

import {
  ContainerComponent,
  ContainerComponentEditButton
} from "~/components/Admin/styles";

export const Container = styled(ContainerComponent)`
  grid-template-columns: 1fr 72px 8fr 2fr 48px;

  > *:nth-child(3) {
    text-align: center;
  }

  > *:nth-child(1) {
    text-align: center;
  }
`;

export const Icon = styled.div`
  * {
    font-size: 48px;
  }
`;

export const EditButton = styled(ContainerComponentEditButton)``;
