import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'crema-clara':    '#fdf9ea',
                'crema-oscuro':   '#faf6ef',
                'verde-claro':    '#a8c5a0',
                'verde':          '#4a7c59',
                'verde-oscuro':   '#2c5038',
                'pizarra-claro':  '#2b3a32',
                'pizarra':        '#25362d',
                'tierra-claro':   '#c4a882',
                'tierra':         '#8b6340',
                'ocre':           '#c49a3c',
                'carbon':         '#2a2118',
            },
        },
    },

    plugins: [forms],
};
