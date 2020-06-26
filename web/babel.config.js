module.exports = {
  plugins: [
    [
      'root-import',
      {
        rootPathPrefix: '~',
        rootPathSuffix: 'src',
      },
    ],
    ['styled-components', { ssr: true, displayName: true, preprocess: false }],
  ],
}
