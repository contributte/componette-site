import merge from 'webpack-merge';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import { Configuration, ProvidePlugin } from 'webpack';

import { DEV, helper, makeEntry } from './utils';
import makeLoaders from './loaders';

const baseConfig: Configuration = {
  entry: makeEntry(),
  module: {
    rules: makeLoaders(),
  },
  target: 'web',
  plugins: [
    new MiniCssExtractPlugin({
      filename: `[name]${DEV ? '' : '.[fullhash]'}.css`,
      chunkFilename: `[id]${DEV ? '' : '.[fullhash]'}.css`,
    }),
    helper.createManifestPlugin(),
    new ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
    }),
  ],
  resolve: {
    extensions: ['.ts', '.js'],
  },
};

const makeConfig = (config: Configuration): Configuration =>
  merge(baseConfig, config);

export default makeConfig;
