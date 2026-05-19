/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './pages/**/*.php',
    './components/**/*.php',
    './templates/**/*.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
  safelist: [
    // Classes générées dynamiquement selon la zone climatique
    'bg-blue-50', 'bg-blue-100', 'bg-blue-200', 'bg-blue-500', 'bg-blue-600',
    'text-blue-600', 'text-blue-700', 'text-blue-800', 'border-blue-200', 'border-blue-300',
    'bg-green-50', 'bg-green-100', 'bg-green-500', 'bg-green-600',
    'text-green-600', 'text-green-700', 'text-green-800', 'border-green-100', 'border-green-200',
    'bg-orange-50', 'bg-orange-100', 'bg-orange-500',
    'text-orange-600', 'text-orange-700', 'text-orange-800', 'border-orange-100', 'border-orange-200',
    'bg-amber-50', 'bg-amber-100', 'text-amber-600', 'text-amber-700',
    'bg-red-50', 'text-red-500', 'text-red-600', 'text-red-700',
    'bg-purple-50', 'bg-purple-100', 'text-purple-500', 'text-purple-600', 'text-purple-700', 'text-purple-800', 'border-purple-100',
    'bg-emerald-100', 'text-emerald-700',
    'text-yellow-400', 'text-gray-400',
  ],
}
