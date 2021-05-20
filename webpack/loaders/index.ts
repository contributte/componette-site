import { RuleSetRule } from 'webpack';

import images from './images';
import scripts from './scripts';
import styles from './styles';

const makeLoaders = (): RuleSetRule[] => [
  ...images(),
  ...scripts(),
  ...styles(),
];

export default makeLoaders;
