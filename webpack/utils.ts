import { resolve } from 'path';

import { Entry } from 'webpack';
import { WebpackHelper } from '@wavevision/nette-webpack';

export const DEV = process.env.NODE_ENV !== 'production';
export const ROOT = resolve(__dirname, '..');

export const helper = new WebpackHelper({
  neonPath: resolve(ROOT, 'app', 'config', 'ext', 'webpack.neon'),
  wwwDir: resolve(ROOT, 'www'),
});

export const makeDevEntry = (): string => {
  const devServerUrl = helper.getDevServerUrl();
  return `webpack-dev-server/client?${devServerUrl.href}`;
};

export const makeEntry = (): Entry => {
  const entry: Entry = {};
  const devEntry = makeDevEntry();
  for (const enabled of helper.getEnabledEntries()) {
    const base = resolve('app', 'assets', `${enabled}.ts`);
    entry[enabled] = DEV ? [devEntry, base] : base;
  }
  return entry;
};
