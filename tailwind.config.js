import typography from '@tailwindcss/typography';

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
        'hw-cream': 'var(--color-cream)',
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
      typography: (theme) => ({
        hw: {
          css: {
            '--tw-prose-body': 'var(--color-text)',
            '--tw-prose-headings': 'var(--color-heading)',
            '--tw-prose-links': 'var(--color-dusty-blue)',
            '--tw-prose-bold': 'var(--color-heading)',
            '--tw-prose-bullets': 'var(--color-dusty-blue)',
            '--tw-prose-counters': 'var(--color-dusty-blue)',
            maxWidth: 'none',
            color: 'var(--color-text)',
            fontFamily: theme('fontFamily.body').join(', '),
            fontSize: 'var(--text-base)',
            lineHeight: 'var(--leading-relaxed)',
            p: {
              marginTop: '1em',
              marginBottom: '1em',
              lineHeight: 'var(--leading-relaxed)',
            },
            'p:first-child': {
              marginTop: '0',
            },
            'p:last-child': {
              marginBottom: '0',
            },
            h2: {
              fontFamily: theme('fontFamily.heading').join(', '),
              fontSize: 'var(--text-h2)',
              fontWeight: '600',
              color: 'var(--color-heading)',
              marginTop: '2.25em',
              marginBottom: '0.75em',
              lineHeight: 'var(--leading-tight)',
            },
            'h2:first-child': {
              marginTop: '0',
            },
            h3: {
              fontFamily: theme('fontFamily.heading').join(', '),
              fontSize: 'var(--text-xl)',
              fontWeight: '600',
              color: 'var(--color-heading)',
              marginTop: '1.75em',
              marginBottom: '0.5em',
              lineHeight: 'var(--leading-snug)',
            },
            'h3:first-child': {
              marginTop: '0',
            },
            ul: {
              marginTop: '1em',
              marginBottom: '1em',
              paddingLeft: '1.25em',
            },
            ol: {
              marginTop: '1em',
              marginBottom: '1em',
              paddingLeft: '1.25em',
            },
            li: {
              marginTop: '0.375em',
              marginBottom: '0.375em',
              lineHeight: 'var(--leading-relaxed)',
            },
            'li > p': {
              marginTop: '0.5em',
              marginBottom: '0.5em',
            },
            a: {
              color: 'var(--color-dusty-blue)',
              textDecoration: 'underline',
              textUnderlineOffset: '0.15em',
              fontWeight: '500',
            },
            'a:hover': {
              color: 'var(--color-heading)',
            },
            strong: {
              color: 'var(--color-heading)',
              fontWeight: '600',
            },
            blockquote: {
              borderLeftColor: 'var(--color-dusty-blue)',
              color: 'var(--color-muted)',
              fontStyle: 'italic',
            },
          },
        },
      }),
    },
  },
  plugins: [typography],
};
