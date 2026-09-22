import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Custom colors if needed
            }
        },
    },

    safelist: [
        'group-hover:opacity-0',
        'group-hover:opacity-100',
        'group-hover:translate-y-0',
        'translate-y-2',
        'opacity-0',
        'opacity-100',
        'z-10',
        'z-20',
        'z-30',
        'line-clamp-3',
    ],

    plugins: [forms, typography, aspectRatio],
};
