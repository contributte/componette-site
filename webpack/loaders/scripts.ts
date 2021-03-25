import { RuleSetRule } from 'webpack';

export default (): RuleSetRule[] => [
  { test: /\.ts$/, exclude: /node_modules/, loader: 'ts-loader' },
];
