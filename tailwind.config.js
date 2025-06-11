const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        fontFamily: {
            saira: ["Saira", "sans-serif"],
            inter: ["Inter", "sans-serif"],
          },
        extend: {
            colors: {
                primary: '#074173',
                secondary: '#B6C7AA',
                accent: '#002379',
                lightGrey: '#607274',
                darkGrey: '#2F3645',
            },
            screens: {
              'laptop': '1100px',
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
