module.exports = {
  theme: {
    extend: {
      colors: {
        blue: {
          '100': '#F1F4F8',
          '200': '#DCE2EC',
          '300': '#C1CCDB',
          '400': '#A5B2C7',
          '500': '#8A99B0',
          '600': '#6B7C97',
          '700': '#526480',
          '800': '#3C4D67',
          '900': '#2C3B52',
        },
        teal: {
          '100': '#E9FBFF',
          '200': '#D1F6FC',
          '300': '#ABE7F1',
          '400': '#88CEDA',
          '500': '#75BBC8',
          '600': '#6AAEBB',
          '700': '#538E9A',
          '800': '#467A85',
          '900': '#2A4C53',
        },
        orange: {
          '100': '#f5dcb6',
          '400': '#fdb353',
          '500': '#FF8900',
          '700': '#ff8b00',
        },
      },
      rotate: {
        '360': '360deg',
      },
      fontSize: {
        '2xs': '.65rem',
      },
      spacing: {
        '72': '18rem',
        '84': '21rem',
        '96': '24rem',
        '128': '32rem',
      },
      minHeight: (theme) => ({
        ...theme('spacing'),
      }),
      flex: {
        '2': '2 2 0%',
      },
    },
    fontFamily: {
      body: [
        'IBM Plex Sans',
        'system-ui',
        '-apple-system',
        'BlinkMacSystemFont',
        'Segoe UI',
        'Roboto',
        'Helvetica Neue',
        'Arial',
        'Noto Sans',
        'sans-serif',
        'Apple Color Emoji',
        'Segoe UI Emoji',
        'Segoe UI Symbol',
        'Noto Color Emoji',
      ],
      headers: [
        'IBM Plex Sans Condensed',
        'system-ui',
        '-apple-system',
        'BlinkMacSystemFont',
        'Segoe UI',
        'Roboto',
        'Helvetica Neue',
        'Arial',
        'Noto Sans',
        'sans-serif',
        'Apple Color Emoji',
        'Segoe UI Emoji',
        'Segoe UI Symbol',
        'Noto Color Emoji',
      ],
    },
    linearGradientDirections: {
      // defaults to these values
      t: 'to top',
      tr: 'to top right',
      r: 'to right',
      br: 'to bottom right',
      b: 'to bottom',
      bl: 'to bottom left',
      l: 'to left',
      tl: 'to top left',
    },
    linearGradientColors: {
      // defaults to {}
      'blue-900-blue-700': ['#2C3B52', '#526480'],
      'transparent-teal-200': ['transparent', '#D1F6FC'],
    },
    customForms: (theme) => ({
      default: {
        input: {
          '&:focus': {
            boxShadow: undefined,
            borderColor: undefined,
          },
        },
        select: {
          '&:focus': {
            boxShadow: undefined,
            borderColor: undefined,
          },
        },
        multiselect: {
          '&:focus': {
            boxShadow: undefined,
            borderColor: undefined,
          },
        },
        textarea: {
          '&:focus': {
            boxShadow: undefined,
            borderColor: undefined,
          },
        },
        checkbox: {
          '&:focus': {
            boxShadow: undefined,
            borderColor: undefined,
          },
          '&:indeterminate': {
            background:
              "url(\"data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='8' height='2' x='4' y='7' rx='1'/%3E%3C/svg%3E\");",
            borderColor: 'transparent',
            backgroundColor: 'currentColor',
            backgroundSize: '100% 100%',
            backgroundPosition: 'center',
            backgroundRepeat: 'no-repeat',
          },
        },
        radio: {
          '&:focus': {
            boxShadow: undefined,
            borderColor: undefined,
          },
        },
      },
    }),
  },
  variants: {
    opacity: ['responsive', 'hover', 'group-hover'],
    textColor: ['responsive', 'hover', 'group-hover'],
    rotate: ['responsive', 'hover', 'group-hover'],
    gridRow: ['responsive', 'hover'],
    gridRowStart: ['responsive', 'hover'],
    gridRowEnd: ['responsive', 'hover'],
  },
  plugins: [
    require('@tailwindcss/custom-forms'),
    require('tailwindcss-gradients'),
  ],
  purge: {
    // TODO: Implement custom purgecss pattern instead of whitelisting
    content: ['./app/**/*.latte'],
    options: {
      whitelist: ['h-4', 'md:mr-4', 'w4', 'ml-2'],
    },
  },
};
