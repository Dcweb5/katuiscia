/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.html",
    "./src/**/*.{html,js}",
    "./js/**/*.js",
    "./resources/views/**/*.blade.php",
  ],
  theme: {
    extend: {
      colors: {
        'dark':       '#3D2B2B',
        'warm':       '#C4967A',
        'warm-light': '#d4ad94',
        'peach':      '#F2E4DA',
        'cream':      '#FDF8F5',
        'mint':       '#C8DDD3',
        'mint-dark':  '#a8c9bc',
        'gray-k':     '#F5F5F0',
        'gray-med':   '#E8E5E0',
        'text-light': '#7A6B6B',
        'text-muted': '#9B8E8E',
        'border-k':   '#E0D6D0',
        'success':    '#5A8F6E',
        'error':      '#C75050',
      },
      fontFamily: {
        'display':  ['"Playfair Display"', 'Georgia', 'serif'],
        'heading':  ['"Cormorant Garamond"', 'Georgia', 'serif'],
        'body':     ['"Inter"', '"Helvetica Neue"', 'Helvetica', 'Arial', 'sans-serif'],
      },
      fontSize: {
        'xs':   '0.75rem',
        'sm':   '0.875rem',
        'base': '1rem',
        'md':   '1.125rem',
        'lg':   '1.25rem',
        'xl':   '1.5rem',
        '2xl':  '2rem',
        '3xl':  '2.5rem',
        '4xl':  '3rem',
        '5xl':  '3.75rem',
        '6xl':  '4.5rem',
      },
      borderRadius: {
        'sm':   '4px',
        'md':   '8px',
        'lg':   '12px',
        'xl':   '20px',
        '2xl':  '28px',
        '3xl':  '40px',
      },
      boxShadow: {
        'sm':   '0 1px 3px rgba(61, 43, 43, 0.06)',
        'md':   '0 4px 12px rgba(61, 43, 43, 0.08)',
        'lg':   '0 8px 30px rgba(61, 43, 43, 0.1)',
        'xl':   '0 16px 50px rgba(61, 43, 43, 0.12)',
        'card': '0 2px 20px rgba(61, 43, 43, 0.06)',
      },
      transitionTimingFunction: {
        'out-expo':  'cubic-bezier(0.16, 1, 0.3, 1)',
        'in-out':    'cubic-bezier(0.65, 0, 0.35, 1)',
        'spring':    'cubic-bezier(0.34, 1.56, 0.64, 1)',
      },
      maxWidth: {
        'site':   '1400px',
        'narrow': '1100px',
        'text':   '680px',
      },
      spacing: {
        'header':        '190px',
        'header-mobile': '84px',
      },
      keyframes: {
        'fade-up': {
          '0%':   { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        'fade-in': {
          '0%':   { opacity: '0' },
          '100%': { opacity: '1' },
        },
        'slide-in-right': {
          '0%':   { opacity: '0', transform: 'translateX(40px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        'float': {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%':      { transform: 'translateY(-12px)' },
        },
        'scale-in': {
          '0%':   { opacity: '0', transform: 'scale(0.92)' },
          '100%': { opacity: '1', transform: 'scale(1)' },
        },
        'shimmer': {
          '0%':   { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
      },
      animation: {
        'fade-up':        'fade-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
        'fade-in':        'fade-in 0.5s ease forwards',
        'slide-in-right': 'slide-in-right 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
        'float':          'float 6s ease-in-out infinite',
        'float-delayed':  'float 6s ease-in-out -3s infinite',
        'scale-in':       'scale-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
        'shimmer':        'shimmer 2s linear infinite',
      },
    },
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: [
      {
        katuiscia: {
          "primary":          "#3D2B2B",
          "primary-content":  "#FDF8F5",
          "secondary":        "#C4967A",
          "secondary-content":"#3D2B2B",
          "accent":           "#C8DDD3",
          "accent-content":   "#3D2B2B",
          "neutral":          "#3D2B2B",
          "neutral-content":  "#FDF8F5",
          "base-100":         "#FDF8F5",
          "base-200":         "#F2E4DA",
          "base-300":         "#E8E5E0",
          "base-content":     "#3D2B2B",
          "info":             "#C8DDD3",
          "success":          "#5A8F6E",
          "warning":          "#C4967A",
          "error":            "#C75050",
        },
      },
    ],
  },
}
