import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import { RuleSetRule } from 'webpack';

export default (dev: boolean): RuleSetRule[] => [
  {
    test: /\.css$/,
    use: [
      {
        loader: MiniCssExtractPlugin.loader,
        options: {
          hmr: dev,
        },
      },
      'css-loader',
      'postcss-loader',
    ],
  },
];
