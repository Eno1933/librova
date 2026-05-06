/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/View/Components/**/*.php",  // kalau ada komponen Blade
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: {
                    light: '#2C5F2E',
                    dark: '#4ADE80',
                    'hover-light': '#214824',
                    'hover-dark': '#22C55E',
                },
                secondary: {
                    light: '#C9A84C',
                    dark: '#FBBF24',
                    'bg-light': '#F5E6B8',
                    'bg-dark': '#3D2F0A',
                },
                bg: {
                    'base-light': '#FAF7F2',
                    'base-dark': '#141410',
                    'surface-light': '#FFFFFF',
                    'surface-dark': '#1E1E19',
                    'muted-light': '#F0EBE1',
                    'muted-dark': '#28271F',
                },
                text: {
                    'primary-light': '#1A1A18',
                    'primary-dark': '#F5F0E8',
                    'secondary-light': '#5C5846',
                    'secondary-dark': '#B8B09A',
                    'muted-light': '#9A9282',
                    'muted-dark': '#7A7362',
                },
                border: {
                    light: '#DDD8CC',
                    dark: '#38352A',
                },
            },
            fontFamily: {
                display: ['Playfair Display', 'serif'],
                heading: ['Lora', 'serif'],
                body: ['Plus Jakarta Sans', 'sans-serif'],
                mono: ['JetBrains Mono', 'monospace'],
            },
        },
    },
    plugins: [],
}