/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    colors: {
      'blue-dark': '#243b52',
      'gray-dark': '#71777e',
      'gray-light': '#f4f5f6',
      'white': '#ffffff',
      'gray': '#cfd1d4',
      }
    },
    fontFamily: {
      // serif: ['Avenir', 'serif'],
      sans: 'Helvetica, Arial, sans-serif',

    },
    fontSize: {
        'xs': '0.75rem',   // 12px
        'sm': '0.875rem',  // 14px
        'base': '1rem',    // 16px
        'lg': '1.125rem',  // 18px
        'xl': '1.25rem',   // 20px
        '2xl': '1.5rem',   // 24px
        '3xl': '1.875rem', // 30px
        '4xl': '2.25rem',  // 36px
        '5xl': '4rem',     // 48px
    },
    extend: {
      spacing: {
        '8xl': '96rem',
        '9xl': '128rem',
      },
      borderRadius: {
        '4xl': '2rem',
      },
      aspectRatio: {
        '3/2': '3 / 2',
      },
      textShadow: {
        'custom': '2px 1px 3px #555555',
      },
    },
  plugins: [
    require('tailwindcss-textshadow'),

  ],
}


// extend: {
    //   colors: {
    //     brand: {
    //       light: '#3ABOFF',
    //       DEFAULT:'#3ABOFF',
    //       dark: '#3ABOFF',
    //     },