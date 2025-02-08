/** @type {import('tailwindcss').Config} */
export const content = [
  "./assets/**/*.js",
  "./templates/**/*.html.twig",
];
export const theme = {
  extend: {
    colors: {
      'success-light': '#A8D5BA',
      'success-dark': '#2E7D32',
      'information-light': '#B3E5FC',
      'information-dark': '#0288D1',
      'error-light': '#FFCDD2',
      'error-dark': '#C62828',
    },
    keyframes: {
      fadeIn: {
        '0%': { opacity: '0' },
        '100%': { opacity: '1' },
      },
      slideIn: {
        '0%': { transform: 'translateX(100%)', opacity: '0' },
        '100%': { transform: 'translateX(0)', opacity: '1' },
      },
      slideOut: {
        '0%': { transform: 'translateX(0)', opacity: '1' },
        '100%': { transform: 'translateX(100%)', opacity: '0' },
      },
    },
    animation: {
      'fade-in' : 'fadeIn 0.5s ease-in-out',
      'slide-in': 'slideIn 0.5s forwards',
      'slide-out': 'slideOut 0.3s forwards',
    },
  },
};
export const safelist = [
  { pattern: /bg-(success|information|error)-light/ },
  { pattern: /text-(success|information|error)-dark/ },
];
export const plugins = [];
