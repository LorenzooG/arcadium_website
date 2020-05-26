import styled, { css } from "styled-components";

import {
  Container as Original,
  Header as OriginalHeader,
  Content as OriginalContent
} from "../styles";

export const Container = styled(Original)`
  padding: 0;
  animation: none;
  display: flex;
`;

export const Header = styled(OriginalHeader)`
  padding: 0;
  margin: 0;
  height: 64px;
  overflow: hidden;
`;

export const Content = styled(OriginalContent)`
  padding: 0;
  margin: 0;
  overflow: hidden;
  ${props =>
    props.defaultHeight
      ? css`
          height: 161px;
        `
      : css`
          flex: 1;
        `}
`;

export const Bar = styled.div`
  height: 100%;
  width: 100%;
  overflow: hidden;
`;
