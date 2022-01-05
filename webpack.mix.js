let mix = require('laravel-mix')

mix.setPublicPath('')

mix
  .sass('assets/scss/style.scss', 'style.css')
  .js('assets/scripts/script.js', 'script.js')
  .version()
