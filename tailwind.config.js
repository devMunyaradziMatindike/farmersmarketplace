import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'xs': ['0.9rem', { lineHeight: '1.2rem' }],      // 0.75rem → 0.9rem (20% increase)
                'sm': ['1.05rem', { lineHeight: '1.5rem' }],     // 0.875rem → 1.05rem (20% increase)
                'base': ['1.2rem', { lineHeight: '1.8rem' }],    // 1rem → 1.2rem (20% increase)
                'lg': ['1.35rem', { lineHeight: '2.1rem' }],     // 1.125rem → 1.35rem (20% increase)
                'xl': ['1.5rem', { lineHeight: '2.1rem' }],      // 1.25rem → 1.5rem (20% increase)
                '2xl': ['1.8rem', { lineHeight: '2.4rem' }],     // 1.5rem → 1.8rem (20% increase)
                '3xl': ['2.28rem', { lineHeight: '2.88rem' }],   // 1.875rem → 2.28rem (20% increase)
                '4xl': ['2.7rem', { lineHeight: '3rem' }],       // 2.25rem → 2.7rem (20% increase)
                '5xl': ['3.6rem', { lineHeight: '1' }],          // 3rem → 3.6rem (20% increase)
                '6xl': ['4.32rem', { lineHeight: '1' }],         // 3.75rem → 4.32rem (20% increase)
                '7xl': ['5.4rem', { lineHeight: '1' }],          // 4.5rem → 5.4rem (20% increase)
                '8xl': ['7.2rem', { lineHeight: '1' }],          // 6rem → 7.2rem (20% increase)
                '9xl': ['9.6rem', { lineHeight: '1' }],          // 8rem → 9.6rem (20% increase)
            },
            colors: {
                primary: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',  // Main green for farmers
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                secondary: {
                    500: '#fbbf24',  // Golden yellow for accent
                },
            },
        },
    },

    plugins: [
        forms,
        require('daisyui'),
    ],

    daisyui: {
        themes: [
            {
                light: {
                    ...require("daisyui/src/theming/themes")["light"],
                    primary: "#22c55e",
                    secondary: "#fbbf24",
                },
                dark: {
                    ...require("daisyui/src/theming/themes")["dark"],
                    primary: "#22c55e",
                    secondary: "#fbbf24",
                },
            },
        ],
    },

    darkMode: 'class',
};
