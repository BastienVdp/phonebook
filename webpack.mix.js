const mix = require('laravel-mix');
const path = require('path');

mix.webpackConfig({
	resolve: {
		alias: {
			'@': path.resolve(__dirname, 'resources/js'),
		}
	},
	watch: true,
	watchOptions: {
		ignored: /node_modules/
	}
})

mix.js('resources/js/index.js', 'public/assets/js')
	.sass("resources/sass/index.scss", "public/assets/css")
	.browserSync("http://phonebook.test")