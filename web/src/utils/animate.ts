import styled, { AnyStyledComponent } from "styled-components";

export default function animate<C extends AnyStyledComponent>(component: C) {
  return styled(component)`
    padding: 0;
    animation: none;
    display: flex;
  `;
}
