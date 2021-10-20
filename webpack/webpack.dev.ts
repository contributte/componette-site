import makeConfig from './webpack.config';
import { helper } from './utils';

const devServerUrl = helper.getDevServerUrl();

export default makeConfig({
  mode: 'development',
  devtool: false,
  devServer: {
    port: Number(devServerUrl.port),
    host: String(devServerUrl.hostname),
    hot: true,
    headers: {
      'Access-Control-Allow-Headers': '*',
      'Access-Control-Allow-Origin': '*',
    },
  },
  output: {
    filename: '[name].js?[fullhash]',
    publicPath: helper.getDevServerPublicPath(),
  },
});
