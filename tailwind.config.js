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
                'xs': ['0.75rem', { lineHeight: '1rem' }],      // Smaller for mobile
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],   // Standard mobile
                'base': ['1rem', { lineHeight: '1.5rem' }],       // Standard desktop
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],   // Large desktop
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],    // Extra large
                '2xl': ['1.5rem', { lineHeight: '2rem' }],       // Headings
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],  // Large headings
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],    // Hero text
                '5xl': ['3rem', { lineHeight: '1' }],            // Display text
                '6xl': ['3.75rem', { lineHeight: '1' }],         // Large display
                '7xl': ['4.5rem', { lineHeight: '1' }],          // Extra large display
                '8xl': ['6rem', { lineHeight: '1' }],             // Massive display
                '9xl': ['8rem', { lineHeight: '1' }],             // Huge display
            },
            screens: {
                'xs': '475px',   // Extra small devices
                'sm': '640px',   // Small devices
                'md': '768px',   // Medium devices
                'lg': '1024px',  // Large devices
                'xl': '1280px',  // Extra large devices
                '2xl': '1536px', // 2X large devices
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
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
            minHeight: {
                'screen-75': '75vh',
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
