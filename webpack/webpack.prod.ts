import OptimizeCssAssetsPlugin from 'optimize-css-assets-webpack-plugin';
import TerserPlugin from 'terser-webpack-plugin';
import { optimize } from 'webpack';

import makeConfig from './webpack.config';
import { helper } from './utils';
import { CleanWebpackPlugin } from 'clean-webpack-plugin';

export default makeConfig({
  mode: 'production',
  devtool: 'source-map',
  output: {
    filename: '[name].js?[hash]',
    path: helper.getOutputPath(),
  },
  optimization: {
    minimizer: [
      new OptimizeCssAssetsPlugin({
        cssProcessorPluginOptions: {
          preset: ['default', { discardComments: { removeAll: true } }],
        },
      }),
      new TerserPlugin({
        extractComments: false,
        sourceMap: true,
        terserOptions: {
          mangle: true,
          output: {
            comments: false,
          },
        },
      }),
    ],
  },
  plugins: [new optimize.OccurrenceOrderPlugin(true), new CleanWebpackPlugin()],
  stats: 'minimal',
});
