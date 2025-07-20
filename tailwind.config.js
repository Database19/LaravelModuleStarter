import defaultTheme from 'tailwindcss/defaultTheme'
import {
  amber,
  blue,
  red,
  yellow,
  green,
  slate,
  gray,
  indigo,
  sky,
  violet,
  rose,
} from 'tailwindcss/colors'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: '#123456',
        red,
        yellow,
        green,
        blue,
        amber,
        sky,
        slate,
        gray,
        indigo,
        violet,
        rose,
      },
    },
  },
  plugins: [],
}
