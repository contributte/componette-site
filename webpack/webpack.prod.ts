import CssMinimizerPlugin from 'css-minimizer-webpack-plugin';
import TerserPlugin from 'terser-webpack-plugin';
import { CleanWebpackPlugin } from 'clean-webpack-plugin';

import makeConfig from './webpack.config';
import { helper } from './utils';

export default makeConfig({
  mode: 'production',
  devtool: 'source-map',
  output: {
    filename: '[name].[fullhash].js',
    path: helper.getOutputPath(),
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
