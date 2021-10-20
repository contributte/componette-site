import { resolve } from 'path';

import { Entry } from 'webpack';
import { WebpackHelper } from '@wavevision/nette-webpack';

export const DEV = process.env.NODE_ENV !== 'production';
export const ROOT = resolve(__dirname, '..');

export const helper = new WebpackHelper({
  neonPath: resolve(ROOT, 'app', 'config', 'ext', 'webpack.neon'),
  wwwDir: resolve(ROOT, 'www'),
});

export const makeEntry = (): Entry => {
  const entry: Entry = {};
  for (const enabled of helper.getEnabledEntries()) {
    entry[enabled] = resolve('app', 'assets', `${enabled}.ts`);
  }
  return entry;
};
