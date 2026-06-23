/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        'hw-white': 'var(--color-white)',
        'hw-text': 'var(--color-text)',
        'hw-heading': 'var(--color-heading)',
        'hw-blush': 'var(--color-blush)',
        'hw-dusty-blue': 'var(--color-dusty-blue)',
        'hw-taupe': 'var(--color-taupe)',
        'hw-navy': 'var(--color-navy)',
        'hw-blush-light': 'var(--color-blush-light)',
        'hw-dusty-blue-light': 'var(--color-dusty-blue-light)',
        'hw-taupe-light': 'var(--color-taupe-light)',
        'hw-border': 'var(--color-border)',
        'hw-muted': 'var(--color-muted)',
      },
      fontFamily: {
        heading: ['var(--font-heading)'],
        body: ['var(--font-body)'],
      },
      fontSize: {
        xs: 'var(--text-xs)',
        sm: 'var(--text-sm)',
        base: 'var(--text-base)',
        lg: 'var(--text-lg)',
        xl: 'var(--text-xl)',
        '2xl': 'var(--text-2xl)',
        '3xl': 'var(--text-3xl)',
        '4xl': 'var(--text-4xl)',
        '5xl': 'var(--text-5xl)',
      },
      spacing: {
        container: 'var(--container-padding)',
        section: 'var(--section-padding-y)',
        header: 'var(--header-height)',
      },
      maxWidth: {
        container: 'var(--container-max)',
      },
      borderRadius: {
        sm: 'var(--radius-sm)',
        md: 'var(--radius-md)',
        lg: 'var(--radius-lg)',
        xl: 'var(--radius-xl)',
        full: 'var(--radius-full)',
      },
    },
  },
  plugins: [],
};
