import { RuleSetRule } from 'webpack';

import scripts from './scripts';
import styles from './styles';

const makeLoaders = (dev: boolean): RuleSetRule[] => [
  ...scripts(),
  ...styles(dev),
];

export default makeLoaders;
