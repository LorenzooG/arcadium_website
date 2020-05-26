import { createGlobalStyle } from "styled-components";

const style = createGlobalStyle`
  body {
    background: #f8f8f8;
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
      'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
      sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  html, body, #root {
    min-height: 100vh;
    min-width: 320px;
    height: fit-content;
  }

  ul, li {
    list-style: none;
  }

  * {
    margin: 0;
    padding: 0;
    color: #333;
    box-sizing: border-box;
  }

  code {
    font-family: source-code-pro, Menlo, Monaco, Consolas, 'Courier New', monospace;
  }
`;

export default style;
