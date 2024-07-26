import CssMinimizerPlugin from 'css-minimizer-webpack-plugin';
import { resolve } from 'path';
import TerserPlugin from 'terser-webpack-plugin';
import { CleanWebpackPlugin } from 'clean-webpack-plugin';

import makeConfig from './webpack.config';
import { ROOT } from './utils';

export default makeConfig({
  mode: 'production',
  devtool: 'source-map',
  output: {
    filename: '[name].[fullhash].js',
    path: resolve(ROOT, 'www/dist'),
  },
  optimization: {
    minimizer: [
      new CssMinimizerPlugin({
        minimizerOptions: {
          preset: ['default', { discardComments: { removeAll: true } }],
        },
      }),
      new TerserPlugin({
        extractComments: false,
        terserOptions: {
          mangle: true,
          output: {
            comments: false,
          },
        },
      }),
    ],
  },
  plugins: [new CleanWebpackPlugin()],
  stats: 'minimal',
});
