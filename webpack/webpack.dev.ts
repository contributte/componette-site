import 'webpack-dev-server';

import makeConfig from './webpack.config';

export default makeConfig({
  mode: 'development',
  devtool: false,
  devServer: {
    port: 9006,
    host: 'localhost',
    hot: true,
    headers: {
      'Access-Control-Allow-Headers': '*',
      'Access-Control-Allow-Origin': '*',
    },
  },
  output: {
    filename: '[name].[fullhash].js',
    publicPath: 'http://localhost:9006/dist/',
  },
});
