/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./**/*.php", "./js/**/*.js"],
    darkMode: 'media',
    theme: {
        extend: {
            maxWidth: { 'zen': '56rem' },
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        '--tw-prose-body': theme('colors.gray.700'),
                        '--tw-prose-headings': theme('colors.gray.900'),
                        '--tw-prose-links': theme('colors.gray.900'),
                        maxWidth: '100%',
                        a: { textDecoration: 'none', borderBottom: '1px solid #e5e7eb', transition: 'border-color 0.2s', '&:hover': { borderBottomColor: '#111827' } },
                    },
                },
                invert: {
                    css: {
                        '--tw-prose-body': theme('colors.gray.300'),
                        '--tw-prose-headings': theme('colors.white'),
                        '--tw-prose-links': theme('colors.white'),
                        a: { borderBottom: '1px solid #374151', '&:hover': { borderBottomColor: '#f3f4f6' } },
                    }
                }
            }),
        }
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],
}
