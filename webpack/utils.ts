import { resolve } from 'path';

import { Entry } from 'webpack';

export const DEV = process.env.NODE_ENV !== 'production';
export const ROOT = resolve(__dirname, '..');

export const entry: Entry = {
  front: resolve('app', 'assets', 'front.ts'),
};
