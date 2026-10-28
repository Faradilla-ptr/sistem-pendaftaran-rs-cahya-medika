/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f0f7fc',
                    100: '#e0eff9',
                    200: '#c2dff3',
                    300: '#94c8e9',
                    400: '#5eaadd',
                    500: '#3b8ecc',
                    600: '#2772b1',
                    700: '#1f4e79', // Deep Classic Navy
                    800: '#1c496d',
                    900: '#0d3b66', // Midnight Ocean Navy
                    950: '#08233e',
                },
                teal: {
                    DEFAULT: '#0d9488',
                    light: '#14b8a6',
                    dark: '#0f766e',
                },
                cyan: {
                    DEFAULT: '#0284c7',
                    light: '#38bdf8',
                    dark: '#0369a1',
                }
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', '-apple-system', 'sans-serif'],
            },
            boxShadow: {
                'soft-sm': '0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02)',
                'soft-md': '0 4px 14px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03)',
                'soft-lg': '0 12px 28px -4px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04)',
                'glow-cyan': '0 0 25px -5px rgba(2, 132, 199, 0.35)',
                'glow-brand': '0 0 25px -5px rgba(13, 59, 102, 0.35)',
            }
        },
    },
    plugins: [
        // forms plugin
    ],
};
