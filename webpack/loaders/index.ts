import { RuleSetRule } from 'webpack';

import images from './images';
import scripts from './scripts';
import styles from './styles';

const makeLoaders = (dev: boolean): RuleSetRule[] => [
  ...images(),
  ...scripts(),
  ...styles(dev),
];

export default makeLoaders;
