import styled from "styled-components";

export const ToastMessage = styled.div`
  color: #fff;
`;

export const Loading = styled.div<{ color?: number }>`
  height: 100%;
  background: rgba(180, 180, 180, ${({ color }) => color ?? 0.1});
  overflow: hidden;
  width: 100%;
  @keyframes bar-animation {
    0% {
      transform: translateX(-100%);
    }
    100% {
      transform: translateX(100%);
    }
  }
  animation: 1.2s bar-animation ease-in-out infinite;
`;

export const RandomLoading = styled(Loading)`
  animation: ${() =>
    Math.floor(Math.random() * 6) + 1 + "s bar-animation ease-in-out infinite"};
`;

export const Bar = styled.div<{ size: string }>`
  height: ${props => props.size};
  width: 100%;
  overflow: hidden;
`;
